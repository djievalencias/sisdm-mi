<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            KantorSeeder::class,
            DepartemenSeeder::class,
            GrupSeeder::class,
            JabatanSeeder::class,
            UserSeeder::class,
            RiwayatJabatanSeeder::class,
        ]);
    }
}
