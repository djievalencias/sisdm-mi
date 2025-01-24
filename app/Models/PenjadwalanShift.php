<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjadwalanShift extends Model
{
    use HasFactory;

    protected $table = 'penjadwalan_shift';

    protected $fillable = [
        'id_user',
        'id_shift',
        'is_ditampilkan',
    ];

    protected $casts = [
        'is_ditampilkan' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift');
    }
}
