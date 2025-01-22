<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_manager',
        'nama',
        'alamat',
        'koordinat_x',
        'koordinat_y',
        'radius',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'id_manager');
    }

    public function departemen()
    {
        return $this->hasMany(Departemen::class, 'id_kantor');
    }
}
