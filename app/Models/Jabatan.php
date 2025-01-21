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
        'id_jabatan',
        'id_grup',
        'nama',
    ];

    public function grup()
    {
        return $this->belongsTo(Grup::class, 'id_grup');
    }
}
