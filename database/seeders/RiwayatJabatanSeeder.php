<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RiwayatJabatanSeeder extends Seeder
{
    public function run(): void
    {
        mt_srand(20260101);

        $jabatanIds = DB::table('jabatan')->pluck('id');
        $now = Carbon::now();
        $rows = [];

        foreach (User::all() as $user) {
            $start = Carbon::parse($user->tanggal_perekrutan ?? $now->copy()->subYears(2));
            $steps = rand(1, 3);

            for ($i = 1; $i <= $steps; $i++) {
                $end = $start->copy()->addMonths(rand(8, 18));
                $isLast = ($i === $steps) || $end->gte($now); // never a future segment

                $rows[] = [
                    'id_user'         => $user->id,
                    'id_jabatan'      => $jabatanIds->random(),
                    'tanggal_mulai'   => $start->toDateString(),
                    'tanggal_selesai' => $isLast ? null : $end->toDateString(),
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];

                if ($isLast) {
                    break;
                }
                $start = $end->copy()->addDay();
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('riwayat_jabatan')->insert($chunk);
        }
    }
}
