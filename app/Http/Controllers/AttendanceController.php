<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\ImageStorage; 
use Symfony\Component\HttpFoundation\Response; 
use Illuminate\Support\Facades\Auth;
use App\Models\Kalender;
use App\Models\PenjadwalanShift;



class AttendanceController extends Controller
{
    /**
     * Tampilkan daftar Attendance.
     */


    /**
     * Form untuk membuat Attendance baru.
     */
    use ImageStorage; // Jika Anda punya trait ini. Jika tidak, silakan buat fungsi upload sendiri.
    public function index()
    {
        // Contoh minimal:
        $attendances = Attendance::all();
        return view('pages.attendance.index', compact('attendances'));
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
        // Validasi
        $request->validate([
            'long'    => 'required',
            'lat'     => 'required',
            'address' => 'required',
            'type'    => 'required|in:in,out',
            'photo'   => 'required|file|image',
        ]);

        $user      = Auth::user(); // User yg login
        $now       = Carbon::now('Asia/Jakarta');
        $tanggal   = $now->format('Y-m-d');
        $hari      = strtolower($now->format('l')); // monday, tuesday, dll
        $type      = $request->type;

        // Cek Hari Libur di tabel 'kalenders' (tipe=hari_libur)
        $isLibur = Kalender::where('tipe','hari_libur')
            ->whereDate('tanggal_mulai','<=',$tanggal)
            ->where(function($q) use($tanggal){
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai','>=',$tanggal);
            })->exists();

        // Jika hari libur, user tidak bisa absen
        if($isLibur){
            return redirect()->back()
                ->with('error','Hari ini libur. Tidak bisa absen.');
        }

// 1. Ambil nama hari (bahasa Inggris) dari Carbon
$dayEnglish = strtolower($now->format('l')); // "monday", "tuesday", dsb

// 2. Peta ke bahasa Indonesia (sesuaikan kolom di DB)
$map = [
    'monday' => 'senin',
    'tuesday' => 'selasa',
    'wednesday' => 'rabu',
    'thursday' => 'kamis',
    'friday' => 'jumat',
    'saturday' => 'sabtu',
    'sunday' => 'minggu'
];

// 3. Ambil nama hari versi DB
$hariDb = $map[$dayEnglish];

// 4. Baru jalankan query ke penjadwalan shift
$shiftAssignment = PenjadwalanShift::where('id_user', $user->id)
    ->whereHas('shift', function($query) use($hariDb) {
        // kolom "senin", "selasa", "rabu", "kamis", dll
        $query->where($hariDb, true);
    })
    ->with('shift')
    ->first();


        // Cek SHIFT user. Asumsikan penjadwalan shift punya field 'id_user','id_shift'
        // shift nya punya field jam mulai & jam selesai + boolean senin, selasa, dsb
    

        if(!$shiftAssignment){
            return redirect()->back()
                ->with('error','Anda tidak memiliki jadwal shift hari ini.');
        }

        $shift      = $shiftAssignment->shift;
        $shiftStart = Carbon::createFromTimeString($shift->waktu_mulai,'Asia/Jakarta')
            ->setDate($now->year,$now->month,$now->day);
        $shiftEnd   = Carbon::createFromTimeString($shift->waktu_selesai,'Asia/Jakarta')
            ->setDate($now->year,$now->month,$now->day);

        // Cek attendance user hari ini
        $attendanceToday = Attendance::where('id_user',$user->id)
            ->whereDate('tanggal',$tanggal)
            ->first();

        if($type=='in')
        {
            // Check-in
            if(!$attendanceToday){
                // Hitung potensi keterlambatan
                $hariKerja = 1.0; // default
                if($now->gt($shiftStart)){
                    // telat = selisih jam
                    $diffInHours = $shiftStart->diffInMinutes($now)/60;
                    if($diffInHours>0 && $diffInHours<=2){
                        $hariKerja -= 0.25; // misal potong 0.25
                    } elseif($diffInHours>2){
                        $hariKerja -= 0.5; // misal potong 0.5
                        // Atau logic lain kalau telat banyak → 0
                    }
                }

                // Simpan ke attendance
                $attendance = Attendance::create([
                    'id_user'          => $user->id,
                    'tanggal'          => $now, // simpan datetime
                    'status'           => false, 
                    'hari_kerja'       => $hariKerja,
                    'jumlah_jam_lembur'=> 0,
                    'is_tanggal_merah' => false, // not libur
                ]);

                // Simpan detail in
                $attendance->detail()->create([
                    'type'    => 'in',
                    'long'    => $request->long,
                    'lat'     => $request->lat,
                    'photo'   => $this->uploadImage($request->file('photo'),$user->nama??$user->id,'attendance'),
                    'address' => $request->address,
                ]);

                return redirect()->back()->with('success','Check-in berhasil');
            } else {
                return redirect()->back()->with('error','Anda sudah check-in hari ini');
            }
        }
        else {
            // type == out (check-out)
            if($attendanceToday && !$attendanceToday->status){
                // Hitung lembur
                $overtimeHours = 0;
                if($now->gt($shiftEnd)){
                    $overtimeHours = $shiftEnd->diffInMinutes($now)/60;
                }

                // Update attendance => status checkout + lembur
                $attendanceToday->update([
                    'status'           => true,
                    'jumlah_jam_lembur'=> $overtimeHours,
                ]);

                // Simpan detail out
                $attendanceToday->detail()->create([
                    'type'    => 'out',
                    'long'    => $request->long,
                    'lat'     => $request->lat,
                    'photo'   => $this->uploadImage($request->file('photo'),$user->nama??$user->id,'attendance'),
                    'address' => $request->address,
                ]);

                return redirect()->back()->with('success','Check-out berhasil');
            } else {
                return redirect()->back()->with('error',
                    $attendanceToday ? 'Anda sudah check-out hari ini' 
                                     : 'Anda belum check-in');
            }
        }
    }

    private function uploadImage($file, $userName, $dir){
        // Silakan sesuaikan logic simpan foto
        $ext = $file->getClientOriginalExtension();
        $filename = uniqid().'_'.$userName.'.'.$ext;
        $file->storeAs($dir,$filename,'public');
        return "storage/$dir/$filename";
    }

    /**
     * Tampilkan satu attendance (beserta detail).
     */
    public function show($id)
    {
        $attendance = Attendance::with('detail','user')->findOrFail($id);
        return view('pages.attendance.show', compact('attendance'));
    }

    /**
     * Form edit Attendance.
     */
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $users      = User::all();
        return view('pages.attendance.edit', compact('attendance','users'));
    }

    /**
     * Update data Attendance ke DB.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'status'  => 'required|boolean',
            'hari_kerja'         => 'nullable|numeric',
            'jumlah_jam_lembur'  => 'nullable|numeric',
            'is_tanggal_merah'   => 'required|boolean',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->all());

        return redirect()->route('attendance.index')->with('success','Attendance berhasil diperbarui.');
    }

    /**
     * Hapus Attendance.
     */
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        return redirect()->route('attendance.index')->with('success','Attendance berhasil dihapus.');
    }
}
