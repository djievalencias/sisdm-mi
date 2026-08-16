<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KantorSeeder extends Seeder
{
    public function run()
    {
        DB::table('kantor')->insert([
            [
                'nama' => 'Kantor Pusat',
                'alamat' => 'Jl. Sudirman No. 1, Jakarta',
                'koordinat_x' => 106.84513,
                'koordinat_y' => -6.21462,
                'radius' => 50.0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama' => 'Kantor Cabang Bandung',
                'alamat' => 'Jl. Asia Afrika No. 10, Bandung',
                'koordinat_x' => 107.619123,
                'koordinat_y' => -6.917464,
                'radius' => 50.0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama' => 'Kantor Cabang Surabaya',
                'alamat' => 'Jl. Raya Darmo No. 15, Surabaya',
                'koordinat_x' => 112.752088,
                'koordinat_y' => -7.257472,
                'radius' => 50.0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
