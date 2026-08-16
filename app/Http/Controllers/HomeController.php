<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\CutiPerizinan;
use App\Models\Pengumuman;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        $pengumuman = Pengumuman::with('creator')->latest()->take(5)->get();

        if ($user->is_admin) {
            return view('home', [
                'pengumuman'    => $pengumuman,
                'attendanceChart' => new \App\Charts\AttendanceChart(),
                'totalKaryawan' => User::where('is_admin', false)->where('is_archived', false)->count(),
                'hadirHariIni'  => Attendance::whereDate('tanggal', today())->count(),
                'belumPulang'   => Attendance::whereDate('tanggal', today())->where('status', false)->count(),
                'cutiPending'   => CutiPerizinan::where('status_pengajuan', 'diajukan')->count(),
            ]);
        }

        return view('home', [
            'pengumuman'    => $pengumuman,
            'absenHariIni'  => Attendance::where('id_user', $user->id)
                ->whereDate('tanggal', today())
                ->first(),
        ]);
    }
}
