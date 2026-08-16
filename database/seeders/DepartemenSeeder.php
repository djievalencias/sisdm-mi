<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartemenSeeder extends Seeder
{
    public function run(): void
    {
        $kantor = DB::table('kantor')->pluck('id', 'nama');
        $now = Carbon::now();

        $rows = [
            ['Kantor Pusat', 'Produksi'],
            ['Kantor Pusat', 'Finishing'],
            ['Kantor Pusat', 'QC'],
            ['Kantor Pusat', 'PPIC'],
            ['Kantor Pusat', 'Gudang'],
            ['Kantor Pusat', 'HRGA'],
            ['Kantor Cabang Bandung', 'Marketing'],
            ['Kantor Cabang Surabaya', 'Purchasing'],
        ];

        DB::table('departemen')->insert(array_map(fn ($r) => [
            'id_kantor'  => $kantor[$r[0]],
            'nama'       => $r[1],
            'created_at' => $now,
            'updated_at' => $now,
        ], $rows));
    }
}
