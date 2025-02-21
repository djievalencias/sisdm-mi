<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $table = 'payroll';

    protected $fillable = [
        'id_user', 
        'tanggal_payroll', 
        'gaji_pokok', 
        'upah_lembur', 
        'gaji_tgl_merah', 
        'upah_lembur_tgl_merah', 
        'iuran_bpjs_kantor', 
        'iuran_bpjs_karyawan', 
        'take_home_pay', 
        'is_reviewed', 
        'reviewed_by', 
        'reviewed_at', 
        'status_pembayaran',
        'dibayar_at', 
    ];

    protected $casts = [
        'tanggal_payroll' => 'date',
        'is_reviewed' => 'boolean',
        'status_pembayaran' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    protected function statusText(): Attribute
    {
        return Attribute::make(
            get: fn (bool $value) => $value ? 'Paid' : 'Pending'
        );
    }

    public function tunjangan()
    {
        return $this->hasMany(Tunjangan::class, 'id_payroll');
    }

    public function potongan()
    {
        return $this->hasMany(Potongan::class, 'id_payroll');
    }
}
