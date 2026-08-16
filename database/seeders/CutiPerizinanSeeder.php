<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CutiPerizinanSeeder extends Seeder
{
    public function run(): void
    {
        mt_srand(20260102);

        $userIds = User::where('is_admin', false)->where('is_archived', false)->pluck('id')->all();
        $now = Carbon::now();

        $keterangan = [
            'Acara keluarga di luar kota',
            'Demam dan flu',
            'Kontrol ke rumah sakit',
            'Mengurus dokumen kependudukan',
            'Anak sakit',
            'Menghadiri pernikahan saudara',
            'Pemulihan pasca operasi kecil',
            'Urusan sekolah anak',
            'Mengantar orang tua berobat',
            'Keperluan pribadi mendesak',
        ];

        $rows = [];
        $statuses = array_merge(
            array_fill(0, 8, 'diajukan'),
            array_fill(0, 12, 'disetujui'),
            array_fill(0, 5, 'ditolak'),
        );

        foreach ($statuses as $i => $status) {
            // pending requests get recent dates so they look actionable
            $back = $status === 'diajukan' ? rand(1, 10) : rand(11, 55);
            $mulai = Carbon::today()->subDays($back);

            $rows[] = [
                'id_user'          => $userIds[array_rand($userIds)],
                'tanggal_mulai'    => $mulai->toDateString(),
                'tanggal_selesai'  => $mulai->copy()->addDays(rand(0, 2))->toDateString(),
                'keterangan'       => $keterangan[$i % count($keterangan)],
                'jenis'            => ['izin', 'izin', 'sakit', 'sakit', 'alpa'][rand(0, 4)],
                'status_pengajuan' => $status,
                'disetujui_oleh'   => $status === 'diajukan' ? null : 1,
                'surat_izin'       => null,
                'created_at'       => $now->copy()->subDays($back),
                'updated_at'       => $now->copy()->subDays(max(0, $back - 2)),
            ];
        }

        DB::table('cuti_perizinan')->insert($rows);
    }
}
