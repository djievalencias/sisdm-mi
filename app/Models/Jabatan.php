<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';

    protected $fillable = [
        'id_grup',
        'nama',
        'description',
    ];

    public function grup()
    {
        return $this->belongsTo(Grup::class, 'id_grup');
    }
}
