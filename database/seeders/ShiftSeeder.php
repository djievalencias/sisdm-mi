<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftSeeder extends Seeder
{
    public function run()
    {
        DB::table('shift')->insert([
            [
                'nama' => 'Pagi',
                'waktu_mulai' => '07:00:00',
                'waktu_selesai' => '15:00:00',
                'senin' => true,
                'selasa' => true,
                'rabu' => true,
                'kamis' => true,
                'jumat' => true,
                'sabtu' => false,
                'minggu' => false,
                'tanggal_mulai' => null,
                'tanggal_berakhir' => null,
                'description' => 'Shift Pagi: 7 AM to 3 PM',
            ],
            [
                'nama' => 'Siang',
                'waktu_mulai' => '15:00:00',
                'waktu_selesai' => '23:00:00',
                'senin' => true,
                'selasa' => true,
                'rabu' => true,
                'kamis' => true,
                'jumat' => true,
                'sabtu' => false,
                'minggu' => false,
                'tanggal_mulai' => null,
                'tanggal_berakhir' => null,
                'description' => 'Shift Siang: 3 PM to 11 PM',
            ],
            [
                'nama' => 'Malam',
                'waktu_mulai' => '23:00:00',
                'waktu_selesai' => '07:00:00',
                'senin' => true,
                'selasa' => true,
                'rabu' => true,
                'kamis' => true,
                'jumat' => true,
                'sabtu' => false,
                'minggu' => false,
                'tanggal_mulai' => null,
                'tanggal_berakhir' => null,
                'description' => 'Shift Malam: 11 PM to 7 AM',
            ],
        ]);
    }
}
