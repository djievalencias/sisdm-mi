<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ImageStorage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    use ImageStorage;

    public function store(Request $request)
    {
        $request->validate([
            'long' => ['required'],
            'lat' => ['required'],
            'address' => ['required'],
            'type' => ['in:in,out,lembur', 'required'],
            'photo' => ['required'],
        ]);

        $photo = $request->file('photo');
        $tanggal = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $attendanceType = $request->type;

        $userAttendanceToday = $request->user()
            ->attendances()
            ->where('tanggal', $tanggal)
            ->first();

        $responseMessage = '';
        $statusCode = Response::HTTP_OK;

        if ($attendanceType == 'in') {
            if (!$userAttendanceToday) {
                $attendance = $request
                    ->user()
                    ->attendances()
                    ->create([
                        'tanggal' => $tanggal,
                        'status' => false,
                        'hari_kerja' => 1.0, // Default workday
                    ]);

                $attendance->detail()->create([
                    'type' => 'in',
                    'long' => $request->long,
                    'lat' => $request->lat,
                    'photo' => $this->uploadImage($photo, $request->user()->name, 'attendance'),
                    'address' => $request->address,
                ]);

                $responseMessage = 'Success';
                $statusCode = Response::HTTP_CREATED;
            } else {
                $responseMessage = 'User has already checked in today';
            }
        } elseif ($attendanceType == 'out') {
            if ($userAttendanceToday && !$userAttendanceToday->status) {
                $userAttendanceToday->update(['status' => true]);

                $userAttendanceToday->detail()->create([
                    'type' => 'out',
                    'long' => $request->long,
                    'lat' => $request->lat,
                    'photo' => $this->uploadImage($photo, $request->user()->name, 'attendance'),
                    'address' => $request->address,
                ]);

                $responseMessage = 'Success';
                $statusCode = Response::HTTP_CREATED;
            } else {
                $responseMessage = $userAttendanceToday
                    ? 'User has already checked out'
                    : 'Please check in first';
                $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
            }
        }

        return response()->json(['message' => $responseMessage], $statusCode);
    }

    public function history(Request $request)
    {
        $request->validate([
            'from' => ['required', 'date'],
            'to' => ['required', 'date'],
            'per_page' => ['nullable', 'integer', 'min:1'],
        ]);

        $perPage = $request->per_page ?? 10;

        $history = $request->user()->attendances()
            ->with('detail')
            ->whereBetween('tanggal', [$request->from, $request->to])
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $history,
        ], Response::HTTP_OK);
    }
}
