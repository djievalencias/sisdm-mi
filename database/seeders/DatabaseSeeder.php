<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * FK-safe order. KalenderSeeder must run before AttendanceSeeder
     * (holiday lookups) and after UserSeeder (created_by).
     */
    public function run(): void
    {
        $this->call([
            KantorSeeder::class,
            DepartemenSeeder::class,
            GrupSeeder::class,
            JabatanSeeder::class,
            UserSeeder::class,
            KantorManagerSeeder::class,
            RiwayatJabatanSeeder::class,
            ShiftSeeder::class,
            KalenderSeeder::class,
            AttendanceSeeder::class,
            CutiPerizinanSeeder::class,
            PengumumanSeeder::class,
            PayrollSeeder::class,
            RoleSeeder::class, // last: backfills roles for every seeded user
        ]);
    }
}
