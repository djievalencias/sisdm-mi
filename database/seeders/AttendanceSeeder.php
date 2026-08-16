<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Kalender;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * ~2 months of weekday history for every active employee, plus today:
     * 20 present, 8 of whom have not clocked out — so the dashboard stats
     * are non-zero on any calendar day.
     */
    public function run(): void
    {
        mt_srand(20260101);

        $holidaySet = Kalender::where('tipe', 'hari_libur')
            ->pluck('tanggal_mulai')
            ->map(fn ($d) => Carbon::parse($d)->toDateString())
            ->flip();

        $userIds = User::where('is_admin', false)->where('is_archived', false)->pluck('id');
        $now = Carbon::now();
        $rows = [];

        $row = function (int $uid, Carbon $date, bool $status, float $hariKerja, int $lembur, bool $merah) use ($now) {
            return [
                'id_user'           => $uid,
                'tanggal'           => $date->toDateString(),
                'status'            => $status,
                'hari_kerja'        => $hariKerja,
                'jumlah_jam_lembur' => $lembur,
                'is_tanggal_merah'  => $merah,
                'created_at'        => $now,
                'updated_at'        => $now,
            ];
        };

        // 1) history: weekdays up to YESTERDAY
        $date = Carbon::today()->subDays(62);
        while ($date->lt(Carbon::today())) {
            if (!$date->isWeekend()) {
                $isHoliday = $holidaySet->has($date->toDateString());

                foreach ($userIds as $uid) {
                    if ($isHoliday) {
                        if (rand(1, 100) <= 20) { // some work the holiday, with the holiday-pay flag
                            $rows[] = $row($uid, $date->copy(), true, 1.00, rand(2, 4), true);
                        }
                        continue;
                    }
                    if (rand(1, 100) <= 8) { // absent
                        continue;
                    }
                    $lembur = rand(1, 100) <= 15 ? rand(1, 3) : 0;
                    $hariKerja = rand(1, 100) <= 90 ? 1.00 : 0.75; // occasional late clock-in
                    $rows[] = $row($uid, $date->copy(), true, $hariKerja, $lembur, false);
                }
            }
            $date->addDay();
        }

        // 2) today: 20 present, first 12 already clocked out, 8 still in
        $today = Carbon::today();
        $todayMerah = $today->isWeekend() || $holidaySet->has($today->toDateString());
        foreach ($userIds->take(20)->values() as $i => $uid) {
            $rows[] = $row($uid, $today, $i < 12, 1.00, 0, $todayMerah);
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            Attendance::insert($chunk);
        }
    }
}
