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

    public function user(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(User::class, Jabatan::class,
            'id_jabatan', 'id', 'id', 'id_user')
            ->where('jabatan.tanggal_selesai', null);
    }
}
