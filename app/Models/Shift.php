<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'waktu_mulai',
        'waktu_selesai',
        'senin',
        'selasa',
        'rabu',
        'kamis',
        'jumat',
        'sabtu',
        'minggu',
        'tanggal_mulai',
        'tanggal_berakhir',
        'description',
    ];

    public function karyawan(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Karyawan::class, Shift::class,
            'id_shift', 'id', 'id', 'id_karyawan')
            ->where('shift.tanggal_berakhir', null);
    }

    public function getNamaFormattedAttribute(): string
    {
        return $this->nama . ' (' . 
            Carbon::parse($this->waktu_mulai)->format('H:i') . ' - ' . 
            Carbon::parse($this->waktu_selesai)->format('H:i') . ')';
    }

    public function isActiveOn(string $day): bool
    {
        $dayMap = [
            'senin' => $this->senin,
            'selasa' => $this->selasa,
            'rabu' => $this->rabu,
            'kamis' => $this->kamis,
            'jumat' => $this->jumat,
            'sabtu' => $this->sabtu,
            'minggu' => $this->minggu,
        ];

        return $dayMap[strtolower($day)] ?? false;
    }

    public function getDurationAttribute(): float
    {
        $start = Carbon::parse($this->waktu_mulai);
        $end = Carbon::parse($this->waktu_selesai);

        if ($end->lessThan($start)) {
            $end->addDay(); // Handles shifts that cross midnight.
        }

        return $end->diffInMinutes($start) / 60;
    }
}
