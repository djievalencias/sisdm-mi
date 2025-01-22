<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grup extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_departemen',
        'nama',
    ];

    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'id_departemen');
    }

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class, 'id_grup');
    }
}
