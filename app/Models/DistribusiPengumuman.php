<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistribusiPengumuman extends Model
{
    use HasFactory;

    protected $table = 'distribusi_pengumuman';

    protected $fillable = [
        'id_pengumuman',
        'id_departemen',
    ];

    public function pengumuman()
    {
        return $this->belongsTo(Pengumuman::class, 'id_pengumuman');
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'id_departemen');
    }
}
