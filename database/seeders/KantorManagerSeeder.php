<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KantorManagerSeeder extends Seeder
{
    /**
     * Runs after UserSeeder: assigns office managers.
     */
    public function run(): void
    {
        DB::table('kantor')->where('nama', 'Kantor Pusat')->update(['id_manager' => 1]);
        DB::table('kantor')->where('nama', 'Kantor Cabang Bandung')->update(['id_manager' => 2]);
        DB::table('kantor')->where('nama', 'Kantor Cabang Surabaya')->update(['id_manager' => 3]);
    }
}
