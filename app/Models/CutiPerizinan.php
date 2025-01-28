<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiPerizinan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'jenis',
        'status_pengajuan',
        'disetujui_oleh',
        'surat_izin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}