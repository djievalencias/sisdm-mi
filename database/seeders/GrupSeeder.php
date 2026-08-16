<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrupSeeder extends Seeder
{
    public function run(): void
    {
        $departemen = DB::table('departemen')->pluck('id', 'nama');
        $now = Carbon::now();

        $rows = [
            ['Produksi',   'Rangka'],
            ['Produksi',   'Amplas'],
            ['Produksi',   'Perakitan'],
            ['Finishing',  'F. Putih'],
            ['Finishing',  'F. Warna'],
            ['QC',         'Inspeksi'],
            ['PPIC',       'Preparation'],
            ['PPIC',       'Penjadwalan'],
            ['Gudang',     'Bahan Baku'],
            ['Gudang',     'Barang Jadi'],
            ['HRGA',       'Staff HRGA'],
            ['Marketing',  'Staff Marketing'],
            ['Purchasing', 'Staff Purchasing'],
        ];

        DB::table('grup')->insert(array_map(fn ($r) => [
            'id_departemen' => $departemen[$r[0]],
            'nama'          => $r[1],
            'created_at'    => $now,
            'updated_at'    => $now,
        ], $rows));
    }
}
