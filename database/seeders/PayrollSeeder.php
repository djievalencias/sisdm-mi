<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PayrollSeeder extends Seeder
{
    /**
     * One payroll per active user for last month; ~1/3 reviewed, some paid.
     * A handful get allowances/deductions with the take-home pay rebalanced.
     */
    public function run(): void
    {
        mt_srand(20260104);

        $now = Carbon::now();
        $tanggal = $now->copy()->subMonthNoOverflow()->startOfMonth()->toDateString();
        $userIds = User::where('is_archived', false)->pluck('id')->values();

        $payrollIds = [];
        foreach ($userIds as $i => $uid) {
            $gaji = rand(76, 190) * 50000;                       // 3.8–9.5 jt
            $lembur = rand(0, 16) * 25000;
            $merah = rand(1, 100) <= 25 ? rand(1, 2) * 150000 : 0;
            $lemburMerah = $merah ? rand(2, 6) * 30000 : 0;
            $bpjsKantor = round($gaji * 0.0424, 2);
            $bpjsKaryawan = round($gaji * 0.03, 2);
            $thp = $gaji + $lembur + $merah + $lemburMerah - $bpjsKaryawan;

            $reviewed = $i % 3 === 0;
            $paid = $reviewed && ($i % 6 === 0);

            $payrollIds[] = DB::table('payroll')->insertGetId([
                'id_user'               => $uid,
                'tanggal_payroll'       => $tanggal,
                'gaji_pokok'            => $gaji,
                'upah_lembur'           => $lembur,
                'gaji_tgl_merah'        => $merah,
                'upah_lembur_tgl_merah' => $lemburMerah,
                'iuran_bpjs_kantor'     => $bpjsKantor,
                'iuran_bpjs_karyawan'   => $bpjsKaryawan,
                'take_home_pay'         => $thp,
                'is_reviewed'           => $reviewed,
                'reviewed_by'           => $reviewed ? 1 : null,
                'reviewed_at'           => $reviewed ? $now->copy()->subDays(rand(1, 9)) : null,
                'status_pembayaran'     => $paid,
                'dibayar_at'            => $paid ? $now->copy()->subDays(rand(0, 5)) : null,
                'created_at'            => $now,
                'updated_at'            => $now,
            ]);
        }

        // allowances/deductions on the first 8 payrolls, take_home_pay rebalanced
        $tunjanganList = ['Tunjangan Makan' => 300000, 'Tunjangan Transportasi' => 250000, 'Tunjangan Jabatan' => 500000];
        $potonganList = ['Kasbon' => 200000, 'Potongan Keterlambatan' => 50000];

        foreach (array_slice($payrollIds, 0, 8) as $pid) {
            $isReviewed = (bool) DB::table('payroll')->where('id', $pid)->value('is_reviewed');
            $delta = 0;

            $tPick = array_slice($tunjanganList, 0, rand(2, 3), true);
            foreach ($tPick as $nama => $nominal) {
                DB::table('tunjangan')->insert([
                    'id_payroll' => $pid, 'nama' => $nama, 'nominal' => $nominal,
                    'status' => $isReviewed, 'created_at' => $now, 'updated_at' => $now,
                ]);
                $delta += $nominal;
            }

            $pPick = array_slice($potonganList, 0, rand(1, 2), true);
            foreach ($pPick as $nama => $nominal) {
                DB::table('potongan')->insert([
                    'id_payroll' => $pid, 'nama' => $nama, 'nominal' => $nominal,
                    'status' => $isReviewed, 'created_at' => $now, 'updated_at' => $now,
                ]);
                $delta -= $nominal;
            }

            DB::table('payroll')->where('id', $pid)->increment('take_home_pay', $delta);
        }
    }
}
