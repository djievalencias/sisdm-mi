<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_jabatan',
        'id_atasan',
        'nama',
        'nik',
        'email',
        'npwp',
        'password',
        'no_telepon',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'tanggal_perekrutan',
        'agama',
        'alamat',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kabupaten_kota',
        'foto_profil',
        'foto_ktp',
        'foto_bpjs_kesehatan',
        'foto_bpjs_ketenagakerjaan',
        'is_aktif',
        'is_admin',
        'is_archived',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'nik',
        'npwp',
        'foto_ktp',
        'foto_bpjs_kesehatan',
        'foto_bpjs_ketenagakerjaan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'tanggal_lahir' => 'date',
        'tanggal_perekrutan' => 'date',
        'tanggal_pemutusan_kontrak' => 'date',
    ];

    public function riwayatJabatan()
    {
        return $this->hasMany(RiwayatJabatan::class, 'id_user');
    }
    
    public function atasan()
    {
        return $this->belongsTo(self::class, 'id_atasan');
    }
    
    public function bawahan()
    {
        return $this->hasMany(self::class, 'id_atasan');
    }
    
    public function attendances()
{
    return $this->hasMany(Attendance::class, 'id_user');
}

    
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift');
    }

    public function payroll()
    {
        return $this->hasMany(Payroll::class);
    }
}
