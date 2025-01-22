<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'id_grup',
        'nama',
    ];

    public function karyawan(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Karyawan::class, Jabatan::class,
            'id_jabatan', 'id', 'id', 'id_karyawan')
            ->where('jabatan.tanggal_selesai', null);
    }
}
