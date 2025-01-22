<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_kantor',
        'nama',
    ];

    public function kantor()
    {
        return $this->belongsTo(Kantor::class, 'id_kantor');
    }

    public function grup()
    {
        return $this->hasMany(Grup::class, 'id_departemen');
    }
}
