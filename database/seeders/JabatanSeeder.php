<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $grup = DB::table('grup')->pluck('id', 'nama');
        $now = Carbon::now();

        $rows = [
            ['Rangka',           'Operator',             'Operator pembuatan rangka furnitur'],
            ['Rangka',           'Leader',               'Pemimpin tim rangka'],
            ['Amplas',           'Operator',             'Operator pengamplasan'],
            ['Perakitan',        'Operator',             'Operator perakitan furnitur'],
            ['Perakitan',        'Supervisor Produksi',  'Pengawas lini produksi'],
            ['F. Putih',         'Operator Finishing',   'Operator finishing dasar'],
            ['F. Warna',         'Operator Finishing',   'Operator finishing warna'],
            ['Inspeksi',         'QC Inspector',         'Pemeriksa kualitas produk'],
            ['Inspeksi',         'Supervisor QC',        'Pengawas kualitas'],
            ['Preparation',      'Admin PPIC',           'Administrasi perencanaan produksi'],
            ['Penjadwalan',      'Staff Penjadwalan',    'Penjadwalan produksi'],
            ['Bahan Baku',       'Operator Gudang',      'Penanganan bahan baku'],
            ['Barang Jadi',      'Admin Gudang',         'Administrasi barang jadi'],
            ['Staff HRGA',       'Staff HR',             'Administrasi SDM dan umum'],
            ['Staff Marketing',  'Sales Executive',      'Penjualan dan hubungan pelanggan'],
            ['Staff Purchasing', 'Staff Purchasing',     'Pengadaan bahan dan jasa'],
        ];

        DB::table('jabatan')->insert(array_map(fn ($r) => [
            'id_grup'     => $grup[$r[0]],
            'nama'        => $r[1],
            'description' => $r[2],
            'created_at'  => $now,
            'updated_at'  => $now,
        ], $rows));
    }
}
