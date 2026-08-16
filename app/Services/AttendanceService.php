<?php

namespace App\Services;

use App\Models\Kalender;
use App\Models\Kantor;
use App\Models\PenjadwalanShift;
use App\Models\Shift;
use App\Models\User;
use Carbon\CarbonInterface;

class AttendanceService
{
    /** Late up to 2 hours costs a quarter day; more costs half a day. */
    public const LATE_SMALL_PENALTY = 0.25;

    public const LATE_LARGE_PENALTY = 0.5;

    private const EARTH_RADIUS_M = 6371000;

    /** English day name -> shift table day-boolean column. */
    private const DAY_COLUMNS = [
        'monday' => 'senin',
        'tuesday' => 'selasa',
        'wednesday' => 'rabu',
        'thursday' => 'kamis',
        'friday' => 'jumat',
        'saturday' => 'sabtu',
        'sunday' => 'minggu',
    ];

    public function resolveKantor(User $user): ?Kantor
    {
        return $user->kantor();
    }

    public function distanceMeters(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $latFrom = deg2rad($lat1);
        $latTo = deg2rad($lat2);
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) ** 2 + cos($latFrom) * cos($latTo) * sin($lonDelta / 2) ** 2;

        return self::EARTH_RADIUS_M * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    /**
     * Check whether the user may clock in/out from the given position.
     *
     * Remote users are always allowed (distance still recorded when the
     * office is known). Users without a resolvable office are allowed but
     * flagged with null distance so the gap is visible in the data.
     *
     * @return array{allowed: bool, distance_m: ?float, is_within_radius: ?bool, reason: ?string}
     */
    public function checkGeofence(User $user, float $lat, float $long): array
    {
        $kantor = $this->resolveKantor($user);

        if (! $kantor) {
            return ['allowed' => true, 'distance_m' => null, 'is_within_radius' => null, 'reason' => null];
        }

        // kantor.koordinat_y is latitude, koordinat_x is longitude; radius is
        // meters. Legacy rows were stored swapped (lat in x) — a latitude can
        // never exceed 90, so detect and unswap.
        [$kantorLat, $kantorLng] = abs((float) $kantor->koordinat_y) <= 90
            ? [(float) $kantor->koordinat_y, (float) $kantor->koordinat_x]
            : [(float) $kantor->koordinat_x, (float) $kantor->koordinat_y];

        $distance = round($this->distanceMeters($lat, $long, $kantorLat, $kantorLng), 2);
        $within = $distance <= (float) $kantor->radius;

        if (! $within && ! $user->is_remote) {
            return [
                'allowed' => false,
                'distance_m' => $distance,
                'is_within_radius' => false,
                'reason' => __('You are :distance m from the office, outside the allowed radius.', ['distance' => round($distance)]),
            ];
        }

        return ['allowed' => true, 'distance_m' => $distance, 'is_within_radius' => $within, 'reason' => null];
    }

    public function isHoliday(CarbonInterface $date): bool
    {
        $day = $date->format('Y-m-d');

        return Kalender::where('tipe', 'hari_libur')
            ->whereDate('tanggal_mulai', '<=', $day)
            ->where(function ($q) use ($day) {
                $q->whereNull('tanggal_selesai')
                    ->orWhere('tanggal_selesai', '>=', $day);
            })
            ->exists();
    }

    public function resolveShift(User $user, CarbonInterface $date): ?Shift
    {
        $dayColumn = self::DAY_COLUMNS[strtolower($date->format('l'))];

        return PenjadwalanShift::where('id_user', $user->id)
            ->whereHas('shift', fn ($q) => $q->where($dayColumn, true))
            ->with('shift')
            ->first()
            ?->shift;
    }

    public function computeHariKerja(CarbonInterface $now, Shift $shift): float
    {
        $shiftStart = $now->copy()->setTimeFromTimeString($shift->waktu_mulai);
        $hariKerja = 1.0;

        if ($now->gt($shiftStart)) {
            $lateHours = $shiftStart->diffInMinutes($now) / 60;
            if ($lateHours > 0 && $lateHours <= 2) {
                $hariKerja -= self::LATE_SMALL_PENALTY;
            } elseif ($lateHours > 2) {
                $hariKerja -= self::LATE_LARGE_PENALTY;
            }
        }

        return $hariKerja;
    }

    public function computeOvertimeHours(CarbonInterface $now, Shift $shift): float
    {
        $shiftEnd = $now->copy()->setTimeFromTimeString($shift->waktu_selesai);

        return $now->gt($shiftEnd) ? $shiftEnd->diffInMinutes($now) / 60 : 0.0;
    }
}
