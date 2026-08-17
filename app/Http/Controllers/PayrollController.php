<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\Potongan;
use App\Models\Tunjangan;
use App\Models\User;
use App\Notifications\PayslipPaid;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Payroll::query()
                ->join('users', 'users.id', '=', 'payroll.id_user')
                ->leftJoin('users as reviewers', 'reviewers.id', '=', 'payroll.reviewed_by')
                ->select('payroll.*', 'users.nama as user_nama', 'reviewers.nama as reviewer_nama')
                ->when($request->filled('id_user'), fn ($q) => $q->where('payroll.id_user', $request->input('id_user')))
                ->when($request->filled('is_reviewed'), fn ($q) => $q->where('payroll.is_reviewed', (bool) $request->input('is_reviewed')))
                ->when($request->filled('status_pembayaran'), fn ($q) => $q->where('payroll.status_pembayaran', (bool) $request->input('status_pembayaran')))
                ->when(preg_match('/^\d{4}-\d{2}$/', (string) $request->input('month')), function ($q) use ($request) {
                    [$year, $month] = explode('-', $request->input('month'));
                    $q->whereYear('payroll.tanggal_payroll', $year)->whereMonth('payroll.tanggal_payroll', $month);
                });

            return DataTables::eloquent($query)
                ->filterColumn('user_nama', fn ($q, $keyword) => $q->where('users.nama', 'like', "%{$keyword}%"))
                ->orderColumn('user_nama', 'users.nama $1')
                ->editColumn('tanggal_payroll', fn ($row) => $row->tanggal_payroll?->translatedFormat('d M Y'))
                ->editColumn('take_home_pay', fn ($row) => number_format($row->take_home_pay, 2))
                ->addColumn('reviewed', fn ($row) => view('pages.payroll._reviewed_pill', ['row' => $row])->render())
                ->addColumn('paid', fn ($row) => view('pages.payroll._paid_pill', ['row' => $row])->render())
                ->addColumn('action', fn ($row) => view('pages.payroll._row_actions', ['row' => $row])->render())
                ->rawColumns(['reviewed', 'paid', 'action'])
                ->toJson();
        }

        $users = User::where('is_archived', false)->orderBy('nama')->get(['id', 'nama']);

        return view('pages.payroll.index', compact('users'));
    }

    public function review($id)
    {
        $payroll = Payroll::with(['user', 'tunjangan', 'potongan'])->findOrFail($id);

        return view('pages.payroll.review', [
            'payroll' => $payroll,
            'tunjangan' => $payroll->tunjangan,
            'potongan' => $payroll->potongan,
        ]);
    }

    public function create()
    {
        $users = User::where('is_archived', false)->orderBy('nama')->get();

        return view('pages.payroll.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => [
                'required',
                'exists:users,id',
                Rule::unique('payroll')->where(function ($query) use ($request) {
                    return $query->where('tanggal_payroll', $request->tanggal_payroll);
                }),
            ],
            'tanggal_payroll' => 'required|date',
            'gaji_pokok' => 'required|numeric',
            'upah_lembur' => 'required|numeric',
            'gaji_tgl_merah' => 'required|numeric',
            'upah_lembur_tgl_merah' => 'required|numeric',
            'iuran_bpjs_kantor' => 'required|numeric',
            'iuran_bpjs_karyawan' => 'required|numeric',
            'take_home_pay' => 'nullable|numeric',
        ]);

        $payroll = Payroll::create($request->all());

        activity()
            ->causedBy(auth()->user())
            ->performedOn($payroll)
            ->withProperties(['id_user' => $payroll->id_user, 'tanggal_payroll' => $payroll->tanggal_payroll->format('Y-m-d'), 'take_home_pay' => $payroll->take_home_pay])
            ->log('payroll.created');

        return redirect()->route('payroll.index')->with('success', __('Payroll created successfully.'));
    }

    public function edit($id)
    {
        $payroll = Payroll::findOrFail($id);

        if ($payroll->is_reviewed) {
            return redirect()->route('payroll.index')->with('error', __('A reviewed payroll can no longer be edited.'));
        }

        $users = User::where('is_archived', false)->orderBy('nama')->get();
        $tunjangan = Tunjangan::where('id_payroll', $id)->get();
        $potongan = Potongan::where('id_payroll', $id)->get();

        return view('pages.payroll.edit', compact('payroll', 'users', 'tunjangan', 'potongan'));
    }

    public function update(Request $request, $id)
    {
        $payroll = Payroll::findOrFail($id);

        $request->validate([
            'id_user' => 'required|exists:users,id',
            'tanggal_payroll' => [
                'required',
                'date',
                Rule::unique('payroll')->where(function ($query) use ($request) {
                    return $query->where('id_user', $request->id_user);
                })->ignore($payroll->id),
            ],
            'gaji_pokok' => 'required|numeric',
            'upah_lembur' => 'required|numeric',
            'gaji_tgl_merah' => 'required|numeric',
            'upah_lembur_tgl_merah' => 'required|numeric',
            'iuran_bpjs_kantor' => 'required|numeric',
            'iuran_bpjs_karyawan' => 'required|numeric',
            'take_home_pay' => 'nullable|numeric',
        ]);

        // Calculate total tunjangan and potongan
        $totalTunjangan = $payroll->tunjangan()->sum('nominal');
        $totalPotongan = $payroll->potongan()->sum('nominal');

        // Calculate final take-home pay
        $finalTakeHomePay = $request->gaji_pokok
            + $request->upah_lembur
            + $request->gaji_tgl_merah
            + $request->upah_lembur_tgl_merah
            + $request->iuran_bpjs_kantor
            + $totalTunjangan
            - $request->iuran_bpjs_karyawan
            - $totalPotongan;

        // Update payroll data
        $payroll->update([
            'gaji_pokok' => $request->gaji_pokok,
            'upah_lembur' => $request->upah_lembur,
            'gaji_tgl_merah' => $request->gaji_tgl_merah,
            'upah_lembur_tgl_merah' => $request->upah_lembur_tgl_merah,
            'iuran_bpjs_kantor' => $request->iuran_bpjs_kantor,
            'iuran_bpjs_karyawan' => $request->iuran_bpjs_karyawan,
            'tanggal_payroll' => $request->tanggal_payroll,
            'take_home_pay' => $finalTakeHomePay,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($payroll)
            ->withProperties(['changed' => array_keys($payroll->getChanges())])
            ->log('payroll.updated');

        return redirect()->route('payroll.index')->with('success', __('Payroll updated successfully.'));
    }

    public function destroy($id)
    {
        $payroll = Payroll::findOrFail($id);

        // Log before deleting so the properties capture what is being removed.
        activity()
            ->causedBy(auth()->user())
            ->performedOn($payroll)
            ->withProperties(['id_user' => $payroll->id_user, 'tanggal_payroll' => $payroll->tanggal_payroll->format('Y-m-d'), 'take_home_pay' => $payroll->take_home_pay])
            ->log('payroll.deleted');

        $payroll->delete();

        return redirect()->route('payroll.index')->with('success', __('Payroll deleted successfully.'));
    }

    public function calculatePayroll(Request $request)
    {
        $id_user = $request->input('id_user');
        $tanggal_payroll = $request->input('tanggal_payroll');
        $UMK = $request->input('umk');

        if (! $id_user || ! $tanggal_payroll || ! $UMK) {
            return response()->json(['error' => 'Missing required inputs'], 400);
        }

        $user = User::findOrFail($id_user);
        $attendances = Attendance::where('id_user', $id_user)
            ->whereMonth('tanggal', date('m', strtotime($tanggal_payroll)))
            ->get();

        $jabatan = strtolower($user->jabatan);
        $gaji_per_hari = $UMK / 25;
        $total_hari_kerja = 0;
        $total_jam_lembur = 0;
        $total_gaji_tgl_merah = 0;
        $total_upah_lembur_tgl_merah = 0;

        foreach ($attendances as $attendance) {
            $total_hari_kerja += $attendance->hari_kerja;

            if ($attendance->jumlah_jam_lembur) {
                if ($attendance->is_tanggal_merah || ! $attendance->status) {
                    $total_upah_lembur_tgl_merah += ($gaji_per_hari / 7) * 2 * $attendance->jumlah_jam_lembur;
                } else {
                    $total_jam_lembur += $attendance->jumlah_jam_lembur;
                }
            }

            if ($attendance->is_tanggal_merah) {
                $total_gaji_tgl_merah += $gaji_per_hari * 2 * $attendance->hari_kerja;
            }
        }

        $gaji_pokok = ($jabatan === 'staff') ? min($total_hari_kerja, 25) * $gaji_per_hari : $total_hari_kerja * $gaji_per_hari;
        $upah_lembur = $total_jam_lembur * 1.5 * ($gaji_per_hari / 7);

        $iuran_bpjs_kantor = $UMK * (0.04 + 0.0089 + 0.037 + 0.003 + 0.02);
        $iuran_bpjs_karyawan = $UMK * (0.01 + 0.02 + 0.01);

        $total_pay = $gaji_pokok + $upah_lembur + $total_gaji_tgl_merah + $total_upah_lembur_tgl_merah + $iuran_bpjs_kantor - $iuran_bpjs_karyawan;

        return response()->json([
            'gaji_pokok' => $gaji_pokok,
            'upah_lembur' => $upah_lembur,
            'gaji_tgl_merah' => $total_gaji_tgl_merah,
            'upah_lembur_tgl_merah' => $total_upah_lembur_tgl_merah,
            'iuran_bpjs_kantor' => $iuran_bpjs_kantor,
            'iuran_bpjs_karyawan' => $iuran_bpjs_karyawan,
            'total_hari_kerja' => $total_hari_kerja,
            'take_home_pay' => $total_pay,
        ]);
    }

    public function markAsReviewed($id)
    {
        $payroll = Payroll::findOrFail($id);

        if ($payroll->is_reviewed) {
            return redirect()->route('payroll.index')->with('error', __('Payroll is already reviewed.'));
        }

        $payroll->update([
            'is_reviewed' => true,
            'reviewed_by' => auth()->user()->id,
            'reviewed_at' => now(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($payroll)
            ->withProperties(['id_user' => $payroll->id_user, 'tanggal_payroll' => $payroll->tanggal_payroll->format('Y-m-d')])
            ->log('payroll.reviewed');

        return redirect()->route('payroll.index')->with('success', __('Payroll marked as reviewed.'));
    }

    public function markAsPaid($id)
    {
        $payroll = Payroll::with(['user', 'reviewer', 'tunjangan', 'potongan'])->findOrFail($id);

        if (! $payroll->is_reviewed) {
            return redirect()->route('payroll.index')->with('error', __('Payroll must be reviewed before it can be marked as paid.'));
        }

        if ($payroll->status_pembayaran) {
            return redirect()->route('payroll.index')->with('error', __('Payroll is already marked as paid.'));
        }

        $payroll->update([
            'status_pembayaran' => true,
            'dibayar_at' => now(),
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($payroll)
            ->withProperties(['id_user' => $payroll->id_user, 'tanggal_payroll' => $payroll->tanggal_payroll->format('Y-m-d'), 'take_home_pay' => $payroll->take_home_pay])
            ->log('payroll.paid');

        if ($payroll->user->email) {
            $payroll->user->notify(new PayslipPaid($payroll));
        }

        return redirect()->route('payroll.index')->with('success', __('Payroll marked as paid.'));
    }

    public function downloadSlip($id)
    {
        $payroll = Payroll::with(['user', 'reviewer', 'tunjangan', 'potongan'])->findOrFail($id);

        if (! $payroll->is_reviewed) {
            return redirect()->route('payroll.index')->with('error', __('Payslip is only available after review.'));
        }

        $filename = 'payslip-'.Str::slug($payroll->user->nama).'-'.$payroll->tanggal_payroll->format('Y-m').'.pdf';

        return Pdf::loadView('pages.payroll.slip', compact('payroll'))->download($filename);
    }
}
