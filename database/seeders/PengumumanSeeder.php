<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        mt_srand(20260103);

        $now = Carbon::now();
        $deptIds = DB::table('departemen')->pluck('id')->all();

        $items = [
            ['Jadwal Cuti Bersama Idul Fitri', 'Cuti bersama ditetapkan H-2 sampai H+2 Idul Fitri. Pengajuan cuti tambahan paling lambat dua minggu sebelumnya.', true],
            ['Perubahan Jam Operasional Gudang', 'Mulai bulan depan gudang bahan baku beroperasi pukul 07.30–16.30. Koordinasikan jadwal pengambilan bahan dengan admin gudang.', false],
            ['Audit ISO 9001 Bulan Depan', 'Tim audit eksternal akan melakukan penilaian di seluruh area produksi. Pastikan dokumen mutu dan checklist harian terisi lengkap.', true],
            ['Pembagian Seragam Baru', 'Seragam kerja baru dapat diambil di HRGA sesuai jadwal per departemen yang tertera di papan pengumuman.', false],
            ['Sosialisasi K3 Area Finishing', 'Wajib bagi seluruh operator finishing. Materi meliputi penggunaan APD dan penanganan bahan kimia.', false],
            ['Pemeliharaan Mesin Amplas', 'Mesin amplas line 2 akan dimatikan untuk perawatan selama dua hari. Jadwal produksi menyesuaikan.', false],
            ['Rekrutmen Internal Supervisor QC', 'Dibuka kesempatan promosi internal untuk posisi Supervisor QC. Daftar melalui HRGA sebelum akhir bulan.', false],
            ['Pengumuman Pemenang 5R Bulanan', 'Selamat kepada tim Perakitan sebagai area kerja terbaik bulan ini. Penghargaan diserahkan saat briefing Senin.', true],
            ['Vaksinasi Gratis Karyawan', 'Bekerja sama dengan puskesmas setempat, vaksinasi influenza gratis tersedia untuk seluruh karyawan.', false],
            ['Batas Pengumpulan Timesheet', 'Timesheet bulanan dikumpulkan paling lambat tanggal 25. Keterlambatan mempengaruhi proses payroll.', false],
            ['Larangan Merokok di Area Produksi', 'Merokok hanya diperbolehkan di area yang telah ditentukan. Pelanggaran dikenakan sanksi sesuai peraturan perusahaan.', false],
            ['Perbaikan Akses Jalan Pabrik', 'Akses masuk kendaraan dialihkan ke gerbang timur selama perbaikan berlangsung minggu ini.', false],
            ['Program Beasiswa Anak Karyawan', 'Pendaftaran beasiswa pendidikan anak karyawan dibuka hingga akhir semester. Syarat lengkap di HRGA.', false],
            ['Survei Kepuasan Karyawan', 'Mohon partisipasi seluruh karyawan mengisi survei tahunan melalui tautan yang dibagikan ke email masing-masing.', false],
        ];

        foreach ($items as $i => [$judul, $pesan, $companyWide]) {
            $id = DB::table('pengumuman')->insertGetId([
                'judul'      => $judul,
                'pesan'      => $pesan,
                'foto'       => null,
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => $now->copy()->subDays(2 * $i),
                'updated_at' => $now->copy()->subDays(2 * $i),
            ]);

            $targets = $companyWide
                ? $deptIds
                : array_intersect_key($deptIds, array_flip((array) array_rand($deptIds, rand(1, 3))));

            DB::table('distribusi_pengumuman')->insert(array_map(fn ($d) => [
                'id_pengumuman' => $id,
                'id_departemen' => $d,
                'created_at'    => $now,
                'updated_at'    => $now,
            ], array_values($targets)));
        }
    }
}
