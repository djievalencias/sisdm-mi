<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JabatanSeeder extends Seeder
{
    public function run()
    {
        $jabatanData = [
            ['nama' => 'Operator', 'id_grup' => '1', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'Operator', 'id_grup' => '2', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'Supervisor', 'id_grup' => '3', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'Operator', 'id_grup' => '4', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'Staff / Admin', 'id_grup' => '5', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];
        
        DB::table('jabatan')->insert($jabatanData);
    }
}
