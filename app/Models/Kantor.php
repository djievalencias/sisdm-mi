<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    use HasFactory;

    protected $table = 'kantor';

    protected $fillable = ['nama', 'alamat', 'koordinat_x', 'koordinat_y', 'radius', 'id_manager'];

    public function manager()
    {
        return $this->belongsTo(User::class, 'id_manager');
    }
}
