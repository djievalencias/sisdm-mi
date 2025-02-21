<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Get users assigned to a specific shift.
     */
    public function getUsersByShift($shiftId)
    {
        $shift = Shift::with('users')->find($shiftId);

        if (!$shift) {
            return response()->json(['message' => 'Shift tidak ditemukan.'], 404);
        }

        return response()->json($shift->users);
    }
}
