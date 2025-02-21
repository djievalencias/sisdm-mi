<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    /**
     * Get all payrolls for a specific user including tunjangan and potongan.
     */
    public function getPayrollByUserId(Request $request, $userId)
    {
        $perPage = $request->query('per_page', 10);

        $payrolls = Payroll::where('id_user', $userId)
            ->select('id', 'id_user', 'tanggal_payroll', 'gaji_pokok', 'upah_lembur', 'gaji_tgl_merah', 'upah_lembur_tgl_merah', 'iuran_bpjs_kantor', 'iuran_bpjs_karyawan', 'take_home_pay', 'is_reviewed', 'reviewed_by', 'reviewed_at', 'status_pembayaran', 'dibayar_at')
            ->with([
                'tunjangan:id,nama,nominal,status',
                'potongan:id,nama,nominal,status'])
            ->orderBy('tanggal_payroll', 'desc')
            ->paginate($perPage);

        if ($payrolls->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada payroll yang ditemukan untuk pengguna ini.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $payrolls
        ], 200);
    }

    /**
     * Get a specific payroll record by ID including tunjangan and potongan.
     */
    public function getPayrollById($payrollId)
    {
        $payroll = Payroll::select('id', 'id_user', 'tanggal_payroll', 'gaji_pokok', 'upah_lembur', 'gaji_tgl_merah', 'upah_lembur_tgl_merah', 'iuran_bpjs_kantor', 'iuran_bpjs_karyawan', 'take_home_pay', 'is_reviewed', 'reviewed_by', 'reviewed_at', 'status_pembayaran', 'dibayar_at')
            ->with([
            'tunjangan:id,nama,nominal,status',
            'potongan:id,nama,nominal,status'])
            ->find($payrollId);

        if (!$payroll) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payroll tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $payroll
        ], 200);
    }
}
