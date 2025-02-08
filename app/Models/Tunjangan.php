<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tunjangan extends Model
{
    use HasFactory;

    protected $table = 'tunjangan';

    protected $fillable = [
        'id_payroll',
        'nama',
        'nominal',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'nominal' => 'decimal:2',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'id_payroll');
    }
}
