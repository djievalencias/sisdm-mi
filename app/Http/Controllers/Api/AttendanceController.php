<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use App\Traits\ImageStorage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{
    use ImageStorage;

    public function __construct(private AttendanceService $attendanceService) {}

    public function store(Request $request)
    {
        $request->validate([
            'long' => ['required', 'numeric', 'between:-180,180'],
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'address' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:in,out'],
            'photo' => ['required', 'file', 'image', 'max:5120'],
        ]);

        $user = $request->user();
        $now = Carbon::now('Asia/Jakarta');
        $tanggal = $now->format('Y-m-d');
        $attendanceType = $request->type;

        // Same guard order as the web flow: holiday -> shift -> geofence,
        // all before the photo is stored so rejects don't orphan files.
        if ($this->attendanceService->isHoliday($now)) {
            return response()->json([
                'message' => __('Today is a holiday. Clock-in is not available.'),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $shift = $this->attendanceService->resolveShift($user, $now);
        if (! $shift) {
            return response()->json([
                'message' => __('You have no shift scheduled today.'),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $geo = $this->attendanceService->checkGeofence($user, (float) $request->lat, (float) $request->long);
        if (! $geo['allowed']) {
            return response()->json([
                'message' => $geo['reason'],
                'distance_m' => $geo['distance_m'],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userAttendanceToday = $user->attendances()
            ->where('tanggal', $tanggal)
            ->first();

        if ($attendanceType == 'in') {
            if ($userAttendanceToday) {
                return response()->json(['message' => 'User has already checked in today'], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $photoPath = $request->file('photo')->store('attendance', 'public');

            $attendance = $user->attendances()->create([
                'tanggal' => $tanggal,
                'status' => false,
                'hari_kerja' => $this->attendanceService->computeHariKerja($now, $shift),
            ]);

            $attendance->detail()->create([
                'type' => 'in',
                'long' => $request->long,
                'lat' => $request->lat,
                'photo' => $photoPath,
                'address' => $request->address,
                'distance_m' => $geo['distance_m'],
                'is_within_radius' => $geo['is_within_radius'],
            ]);

            return response()->json(['message' => 'Success'], Response::HTTP_CREATED);
        }

        // type == out
        if (! $userAttendanceToday || $userAttendanceToday->status) {
            return response()->json([
                'message' => $userAttendanceToday
                    ? 'User has already checked out'
                    : 'Please check in first',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $photoPath = $request->file('photo')->store('attendance', 'public');

        $userAttendanceToday->update([
            'status' => true,
            'jumlah_jam_lembur' => $this->attendanceService->computeOvertimeHours($now, $shift),
        ]);

        $userAttendanceToday->detail()->create([
            'type' => 'out',
            'long' => $request->long,
            'lat' => $request->lat,
            'photo' => $photoPath,
            'address' => $request->address,
            'distance_m' => $geo['distance_m'],
            'is_within_radius' => $geo['is_within_radius'],
        ]);

        return response()->json(['message' => 'Success'], Response::HTTP_CREATED);
    }

    public function history(Request $request)
    {
        $request->validate([
            'from' => ['required', 'date'],
            'to' => ['required', 'date'],
        ]);

        $history = $request->user()->attendances()
            ->with('detail')
            ->whereBetween('tanggal', [$request->from, $request->to])
            ->get();

        return response()->json([
            'message' => "List of user's attendance history",
            'data' => $history,
        ], Response::HTTP_OK);
    }
}
