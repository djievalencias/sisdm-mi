<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'pesan',
        'foto',
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
