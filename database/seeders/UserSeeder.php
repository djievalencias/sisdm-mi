<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'nama' => 'John Doe',
                'nik' => '1234567890123456',
                'email' => 'johndoe@example.com',
                'npwp' => '9876543210123456',
                'password' => Hash::make('password123'),
                'no_telepon' => '081234567890',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-05-15',
                'tanggal_perekrutan' => '2020-01-10',
                'agama' => 'Islam',
                'pendidikan' => 'S1 Teknik Informatika',
                'status_perkawinan' => 'Menikah',
                'alamat' => 'Jl. Merdeka No. 10',
                'kelurahan' => 'Menteng',
                'kecamatan' => 'Jakarta Pusat',
                'kabupaten_kota' => 'DKI Jakarta',
                'is_aktif' => true,
                'is_admin' => true,
                'is_archived' => false,
                'is_remote' => false,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama' => 'Jane Smith',
                'nik' => '2233445566778899',
                'email' => 'janesmith@example.com',
                'npwp' => '1122334455667788',
                'password' => Hash::make('password123'),
                'no_telepon' => '081298765432',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1995-07-20',
                'tanggal_perekrutan' => '2021-06-15',
                'agama' => 'Kristen',
                'pendidikan' => 'S1 Manajemen',
                'status_perkawinan' => 'Belum menikah',
                'alamat' => 'Jl. Asia Afrika No. 20',
                'kelurahan' => 'Braga',
                'kecamatan' => 'Bandung Wetan',
                'kabupaten_kota' => 'Bandung',
                'is_aktif' => true,
                'is_admin' => false,
                'is_archived' => false,
                'is_remote' => true,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama' => 'Michael Johnson',
                'nik' => '3344556677889900',
                'email' => 'michaelj@example.com',
                'npwp' => '9988776655443322',
                'password' => Hash::make('password123'),
                'no_telepon' => '081312345678',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1988-12-10',
                'tanggal_perekrutan' => '2018-03-20',
                'agama' => 'Hindu',
                'pendidikan' => 'S2 Ekonomi',
                'status_perkawinan' => 'Menikah',
                'alamat' => 'Jl. Darmo No. 30',
                'kelurahan' => 'Tegalsari',
                'kecamatan' => 'Tegalsari',
                'kabupaten_kota' => 'Surabaya',
                'is_aktif' => true,
                'is_admin' => false,
                'is_archived' => false,
                'is_remote' => false,
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
