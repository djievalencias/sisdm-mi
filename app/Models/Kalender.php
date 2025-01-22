<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kalender extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'tanggal_mulai',
        'tanggal_selesai',
        'tipe',
        'created_by',
        'updated_by'
    ];

    public function createdBy()
    {
        return $this->belongsTo(Karyawan::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Karyawan::class, 'updated_by');
    }
}
