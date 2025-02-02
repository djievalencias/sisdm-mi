<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AttendanceDetail extends Model
{
    use HasFactory;

    protected $table = 'attendance_details';

    protected $fillable = [
        'id_attendance',
        'long',
        'lat',
        'address',
        'photo',
        'type',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class, 'id_attendance');
    }
}
