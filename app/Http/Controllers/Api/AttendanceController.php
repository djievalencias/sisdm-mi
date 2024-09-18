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
            'type' => ['in:in,out', 'required'],
            'photo' => ['required']
        ]);

        $photo = $request->file('photo');
        $today = Carbon::now('Asia/Seoul')->startOfDay();
        $attendanceType = $request->type;
        $userAttendanceToday = $request->user()
            ->attendances()
            ->whereDate('created_at', $today)
            ->first();

        $responseMessage = '';
        $statusCode = Response::HTTP_OK; // Default status code

        if ($attendanceType == 'in') {
            if (!$userAttendanceToday) {
                $attendance = $request
                    ->user()
                    ->attendances()
                    ->create(['status' => false]);

                $attendance->detail()->create([
                    'type' => 'in',
                    'long' => $request->long,
                    'lat' => $request->lat,
                    'photo' => $this->uploadImage($photo, $request->user()->name, 'attendance'),
                    'address' => $request->address
                ]);

                $responseMessage = 'Success';
                $statusCode = Response::HTTP_CREATED;
            } else {
                $responseMessage = 'User has been checked in';
            }
        } elseif ($attendanceType == 'out') {
            if ($userAttendanceToday) {
                if ($userAttendanceToday->status) {
                    $responseMessage = 'User has been checked out';
                } else {
                    $userAttendanceToday->update(['status' => true]);

                    $userAttendanceToday->detail()->create([
                        'type' => 'out',
                        'long' => $request->long,
                        'lat' => $request->lat,
                        'photo' => $this->uploadImage($photo, $request->user()->name, 'attendance'),
                        'address' => $request->address
                    ]);

                    $responseMessage = 'Success';
                    $statusCode = Response::HTTP_CREATED;
                }
            } else {
                $responseMessage = 'Please do check in first';
                $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
            }
        }

        return response()->json(['message' => $responseMessage], $statusCode);
    }

    public function history(Request $request)
    {
        $request->validate([
            'from' => ['required'],
            'to' => ['required'],
        ]);

        $history = $request->user()->attendances()->with('detail')
            ->whereBetween(DB::raw('DATE(created_at)'), [$request->from, $request->to])
            ->get();

        // Formatting should be handled by model accessors
        return response()->json([
            'message' => "list of presences by user",
            'data' => $history,
        ], Response::HTTP_OK);
    }
}
