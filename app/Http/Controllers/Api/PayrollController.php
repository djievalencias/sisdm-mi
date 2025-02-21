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
    public function getPayrollByUserId($userId)
    {
        $payrolls = Payroll::where('id_user', $userId)
            ->with(['tunjangan', 'potongan'])
            ->orderBy('tanggal_payroll', 'desc')
            ->get();

        if ($payrolls->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No payroll records found for this user.'
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
        $payroll = Payroll::with(['tunjangan', 'potongan'])
            ->find($payrollId);

        if (!$payroll) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payroll record not found.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $payroll
        ], 200);
    }
}
