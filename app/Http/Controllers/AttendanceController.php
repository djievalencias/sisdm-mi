<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use App\Services\AttendanceService;
use App\Traits\ImageStorage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    use ImageStorage;

    public function __construct(private AttendanceService $attendanceService) {}

    public function index(Request $request)
    {
        $attendances = Attendance::with('user')
            ->when($request->filled('id_user'), fn ($q) => $q->where('id_user', $request->input('id_user')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('tanggal', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('tanggal', '<=', $request->input('to')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', (bool) $request->input('status')))
            ->when($request->filled('is_tanggal_merah'), fn ($q) => $q->where('is_tanggal_merah', (bool) $request->input('is_tanggal_merah')))
            ->get();

        $users = User::where('is_archived', false)->orderBy('nama')->get(['id', 'nama']);

        return view('pages.attendance.index', compact('attendances', 'users'));
    }

    /**
     * Tampilkan form create attendance.
     */
    public function create()
    {
        // Form untuk absen. Bisa menampilkan form 'type' (in/out), lat/long, dsb
        return view('pages.attendance.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'long' => 'required|numeric|between:-180,180',
            'lat' => 'required|numeric|between:-90,90',
            'address' => 'required|string|max:255',
            'type' => 'required|in:in,out',
            'photo' => 'required|file|image|max:5120',
        ]);

        $user = Auth::user();
        $now = Carbon::now('Asia/Jakarta');
        $tanggal = $now->format('Y-m-d');
        $type = $request->type;

        if ($this->attendanceService->isHoliday($now)) {
            return redirect()->back()
                ->with('error', __('Today is a holiday. Clock-in is not available.'));
        }

        $shift = $this->attendanceService->resolveShift($user, $now);
        if (! $shift) {
            return redirect()->back()
                ->with('error', __('You have no shift scheduled today.'));
        }

        $geo = $this->attendanceService->checkGeofence($user, (float) $request->lat, (float) $request->long);
        if (! $geo['allowed']) {
            return redirect()->back()->with('error', $geo['reason']);
        }

        $attendanceToday = Attendance::where('id_user', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($type == 'in') {
            if ($attendanceToday) {
                return redirect()->route('attendance.index')->with('error', __('You have already checked in today.'));
            }

            $photoPath = $request->file('photo')->store('attendance', 'public');

            $attendance = Attendance::create([
                'id_user' => $user->id,
                'tanggal' => $now,
                'status' => false,
                'hari_kerja' => $this->attendanceService->computeHariKerja($now, $shift),
                'jumlah_jam_lembur' => 0,
                'is_tanggal_merah' => false,
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

            return redirect()->route('attendance.index')->with('success', __('Check-in successful.'));
        }

        // type == out (check-out)
        if (! $attendanceToday || $attendanceToday->status) {
            return redirect()->route('attendance.index')->with('error',
                $attendanceToday ? __('You have already checked out today.')
                                 : __('You have not checked in yet.'));
        }

        $photoPath = $request->file('photo')->store('attendance', 'public');

        $attendanceToday->update([
            'status' => true,
            'jumlah_jam_lembur' => $this->attendanceService->computeOvertimeHours($now, $shift),
        ]);

        $attendanceToday->detail()->create([
            'type' => 'out',
            'long' => $request->long,
            'lat' => $request->lat,
            'photo' => $photoPath,
            'address' => $request->address,
            'distance_m' => $geo['distance_m'],
            'is_within_radius' => $geo['is_within_radius'],
        ]);

        return redirect()->route('attendance.index')->with('success', __('Check-out successful.'));
    }

    /**
     * Tampilkan satu attendance (beserta detail).
     */
    public function show($id)
    {
        $attendance = Attendance::with('detail', 'user')->findOrFail($id);

        return view('pages.attendance.show', compact('attendance'));
    }

    /**
     * Form edit Attendance.
     */
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $users = User::where('is_archived', false)->orderBy('nama')->get();

        return view('pages.attendance.edit', compact('attendance', 'users'));
    }

    /**
     * Update data Attendance ke DB.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'status' => 'required|boolean',
            'hari_kerja' => 'nullable|numeric',
            'jumlah_jam_lembur' => 'nullable|numeric',
            'is_tanggal_merah' => 'required|boolean',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->all());

        return redirect()->route('attendance.index')->with('success', __('Attendance updated successfully.'));
    }

    /**
     * Hapus Attendance.
     */
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($attendance)
            ->withProperties(['id_user' => $attendance->id_user, 'tanggal' => $attendance->tanggal->format('Y-m-d')])
            ->log('attendance.deleted');

        $attendance->delete();

        return redirect()->route('attendance.index')->with('success', __('Attendance deleted successfully.'));
    }
}
