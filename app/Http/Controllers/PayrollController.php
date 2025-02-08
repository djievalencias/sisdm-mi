<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\Tunjangan;
use App\Models\Potongan;
use App\Models\User;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    private function validatePayrollData(Request $request, $isNew = true)
    {
        $rules = [
            'gaji_pokok' => 'numeric',
            'upah_lembur' => 'numeric',
            'gaji_tgl_merah' => 'numeric',
            'upah_lembur_tgl_merah' => 'numeric',
            'iuran_bpjs_kantor' => 'numeric',
            'iuran_bpjs_karyawan' => 'numeric',
        ];

        if ($isNew) {
            $rules = array_merge($rules, [
                'id_user' => 'required|exists:users,id',
                'tanggal_payroll' => 'required|date',
            ]);
        }

        return $request->validate($rules);
    }

    public function index()
    {
        $payrolls = Payroll::with('user')->get();
        return view('pages.payroll.index', compact('payrolls'));
    }

    public function show($id)
    {
        $payroll = Payroll::with(['user', 'tunjangan', 'potongan'])->findOrFail($id);

        return view('pages.payroll.show', [
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
        $data = $request->validate([
            'id_user' => 'required|exists:users,id',
            'tanggal_payroll' => 'required|date',
            'gaji_pokok' => 'required|numeric',
            'upah_lembur' => 'required|numeric',
            'gaji_tgl_merah' => 'required|numeric',
            'upah_lembur_tgl_merah' => 'required|numeric',
            'iuran_bpjs_kantor' => 'required|numeric',
            'iuran_bpjs_karyawan' => 'required|numeric',
            'take_home_pay' => 'required|numeric',
        ]);

        Payroll::create($data);

        return redirect()->route('payroll.index')->with('success', 'Payroll created successfully.');
    }

    public function edit($id)
    {
        $payroll = Payroll::findOrFail($id);
        $users = User::all();
        $tunjangan = Tunjangan::where('id_payroll', $id)->get();
        $potongan = Potongan::where('id_payroll', $id)->get();
        return view('pages.payroll.edit', compact('payroll', 'users', 'tunjangan', 'potongan'));
    }

    public function update(Request $request, $id)
    {
        $payroll = Payroll::findOrFail($id);

        // Base take-home pay from user input
        $baseTakeHomePay = $request->input('take_home_pay');

        // Sum of tunjangan
        $totalTunjangan = $payroll->tunjangan()->sum('nominal');

        // Sum of potongan
        $totalPotongan = $payroll->potongan()->sum('nominal');

        // Final take-home pay calculation
        $finalTakeHomePay = $baseTakeHomePay + $totalTunjangan - $totalPotongan;

        // Update payroll with final take-home pay
        $payroll->update([
            'take_home_pay' => $finalTakeHomePay,
            'tanggal_payroll' => $request->tanggal_payroll,
            'umk' => $request->umk,
        ]);

        return redirect()->route('payroll.index')->with('success', 'Payroll updated successfully.');
    }

    public function destroy($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();
        return redirect()->route('payroll.index')->with('success', 'Payroll deleted successfully');
    }

    public function calculatePayroll(Request $request)
    {
        $id_user = $request->input('id_user');
        $tanggal_payroll = $request->input('tanggal_payroll');
        $UMK = $request->input('umk');

        // Validate inputs
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
}
