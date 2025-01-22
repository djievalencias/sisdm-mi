<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiPerizinan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_karyawan',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'jenis',
        'status_pengajuan',
        'disetujui_oleh',
        'surat_izin',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(Karyawan::class, 'disetujui_oleh');
    }
}
