<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\Tunjangan;
use App\Models\Potongan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::with('user')->get();
        return view('pages.payroll.index', compact('payrolls'));
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
        $users = User::all();
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

        Payroll::create($request->all());

        return redirect()->route('payroll.index')->with('success', 'Payroll berhasil dibuat.');
    }

    public function edit($id)
    {
        $payroll = Payroll::findOrFail($id);

        if ($payroll->is_reviewed) {
            return redirect()->route('payroll.index')->with('error', 'Payroll yang sudah direview tidak dapat diedit.');
        }

        $users = User::all();
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

        return redirect()->route('payroll.index')->with('success', 'Payroll berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();
        return redirect()->route('payroll.index')->with('success', 'Payroll berhasil dihapus.');
    }

    public function calculatePayroll(Request $request)
    {
        $id_user = $request->input('id_user');
        $tanggal_payroll = $request->input('tanggal_payroll');
        $UMK = $request->input('umk');

        if (!$id_user || !$tanggal_payroll || !$UMK) {
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
                if ($attendance->is_tanggal_merah || !$attendance->status) {
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

        // Update payroll review status
        $payroll->update([
            'is_reviewed' => true,
            'reviewed_by' => auth()->user()->id,
            'reviewed_at' => now(),
        ]);

        return redirect()->route('payroll.index')->with('success', 'Payroll berhasil ditandai sudah direview.');
    }

    public function markAsPaid($id)
    {
        $payroll = Payroll::findOrFail($id);

        // Mark payroll as paid
        $payroll->update([
            'status_pembayaran' => true,
            'dibayar_at' => now(),
        ]);

        return redirect()->route('payroll.index')->with('success', 'Payroll berhasil ditandai sudah dibayar.');
    }
}
