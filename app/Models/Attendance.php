<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'id_user',
        'tanggal',
        'status',
        'hari_kerja',
        'jumlah_jam_lembur',
        'is_tanggal_merah',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status' => 'boolean',
        'hari_kerja' => 'decimal:2',
        'jumlah_jam_lembur' => 'decimal:2',
        'is_tanggal_merah' => 'boolean',
    ];

    public function detail()
    {
        return $this->hasMany(AttendanceDetail::class, 'id_attendance');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public static function countAttendance(bool $status): int
    {
        // Assuming 'status' is the column indicating "in" or "out"
        return self::where('status', $status)->count();
    }
}
