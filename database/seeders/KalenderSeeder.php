<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KalenderSeeder extends Seeder
{
    /**
     * National holidays + a couple of live events.
     *
     * NOTE: every 'hari_libur' row sets tanggal_selesai — AttendanceController
     * treats a NULL end date as an open-ended holiday and would block
     * clock-in forever after the start date.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $y = $now->year;
        $rows = [];

        $mk = function (string $judul, Carbon $mulai, ?Carbon $selesai, string $tipe, string $repeat, ?Carbon $until = null) use ($now) {
            return [
                'judul'           => $judul,
                'tanggal_mulai'   => $mulai->toDateString(),
                'tanggal_selesai' => $selesai?->toDateString(),
                'tipe'            => $tipe,
                'repeat_type'     => $repeat,
                'repeat_until'    => $until?->toDateString(),
                'created_by'      => 1,
                'updated_by'      => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        };

        // fixed-date national holidays (yearly)
        foreach ([
            ['Tahun Baru Masehi', 1, 1],
            ['Hari Buruh Internasional', 5, 1],
            ['Hari Lahir Pancasila', 6, 1],
            ['Hari Kemerdekaan RI', 8, 17],
            ['Hari Raya Natal', 12, 25],
        ] as [$judul, $m, $d]) {
            $t = Carbon::create($y, $m, $d);
            $rows[] = $mk($judul, $t, $t->copy(), 'hari_libur', 'yearly');
        }

        // movable holidays, approximate current-year dates (demo data)
        foreach ([
            ['Isra Mikraj Nabi Muhammad SAW', 1, 27],
            ['Tahun Baru Imlek', 2, 17],
            ['Hari Suci Nyepi', 3, 19],
            ['Hari Raya Idul Fitri', 3, 20, 1],   // 2-day span
            ['Kenaikan Isa Almasih', 5, 14],
            ['Hari Raya Waisak', 5, 31],
            ['Hari Raya Idul Adha', 5, 27],
            ['Tahun Baru Islam 1 Muharram', 6, 16],
        ] as $h) {
            $t = Carbon::create($y, $h[1], $h[2]);
            $end = $t->copy()->addDays($h[3] ?? 0);
            $rows[] = $mk($h[0], $t, $end, 'hari_libur', 'never');
        }

        // live-looking events near today
        $rows[] = $mk('Meeting Koordinasi Produksi', $now->copy()->next(Carbon::MONDAY), null, 'meeting', 'weekly', $now->copy()->addMonths(3));
        $rows[] = $mk('Town Hall Karyawan', $now->copy()->addDays(10), null, 'acara', 'never');

        DB::table('kalender')->insert($rows);
    }
}
