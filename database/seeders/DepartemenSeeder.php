<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DepartemenSeeder extends Seeder
{
    public function run()
    {
        $kantorMap = DB::table('kantor')->pluck('id', 'nama');
        
        $departemenData = [
            ['nama' => 'Produksi', 'id_kantor' => $kantorMap['Kantor Pusat'], 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'PPIC', 'id_kantor' => $kantorMap['Kantor Cabang Bandung'], 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama' => 'QC', 'id_kantor' => $kantorMap['Kantor Cabang Surabaya'], 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];
        
        DB::table('departemen')->insert($departemenData);
    }
}