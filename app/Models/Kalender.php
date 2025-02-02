<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kalender extends Model
{
    use HasFactory;

    protected $table = 'kalender';

    protected $fillable = [
        'judul',
        'tanggal_mulai',
        'tanggal_selesai',
        'tipe',
        'repeat_type',
        'repeat_until',
        'created_by',
        'updated_by'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
