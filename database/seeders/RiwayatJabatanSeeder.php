<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiwayatJabatanSeeder extends Seeder
{
    public function run()
    {
        $users = DB::table('users')->pluck('id');
        $jabatanMap = DB::table('jabatan')->pluck('id', 'nama');
        
        foreach ($users as $userId) {
            DB::table('riwayat_jabatan')->insert([
                [
                    'id_user' => $userId,
                    'id_jabatan' => '1',
                    'tanggal_mulai' => Carbon::now()->subYears(3),
                    'tanggal_selesai' => Carbon::now()->subYear(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id_user' => $userId,
                    'id_jabatan' => '2',
                    'tanggal_mulai' => Carbon::now()->subYear(),
                    'tanggal_selesai' => Carbon::now()->subMonths(6),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'id_user' => $userId,
                    'id_jabatan' => '3',
                    'tanggal_mulai' => Carbon::now()->subMonths(6),
                    'tanggal_selesai' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }
    }
}
