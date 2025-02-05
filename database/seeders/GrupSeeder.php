<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GrupSeeder extends Seeder
{
    public function run()
    {
        $departemenMap = DB::table('departemen')->pluck('id', 'nama');
        $grupData = [
            ['nama' => 'F. Putih', 'id_departemen' => $departemenMap['Produksi'], 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'Preparation', 'id_departemen' => $departemenMap['PPIC'], 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'Staff', 'id_departemen' => $departemenMap['Produksi'], 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'Amplas', 'id_departemen' => $departemenMap['Produksi'], 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'Staff', 'id_departemen' => $departemenMap['QC'], 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];
        
        DB::table('grup')->insert($grupData);
    }
}
