<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_karyawan',
        'tanggal_payroll',
        'gaji_pokok',
        'upah_lembur',
        'gaji_tgl_merah',
        'upah_lembur_tgl_merah',
        'bpjs_kes_perusahaan',
        'bpjs_jkk_perusahaan',
        'bpjs_jht_perusahaan',
        'bpjs_jkm_perusahaan',
        'bpjs_jp_perusahaan',
        'bpjs_kes_karyawan',
        'is_reviewed',
        'status',
    ];

    protected $casts = [
        'tanggal_payroll' => 'date',
        'is_reviewed' => 'boolean',
        'status' => 'boolean',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }

    protected function statusText(): Attribute
    {
        return Attribute::make(
            get: fn (bool $value) => $value ? 'Paid' : 'Pending'
        );
    }
}
