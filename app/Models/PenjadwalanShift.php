<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjadwalanShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_karyawan',
        'id_shift',
        'is_ditampilkan',
    ];

    protected $casts = [
        'is_ditampilkan' => 'boolean',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift');
    }
}
