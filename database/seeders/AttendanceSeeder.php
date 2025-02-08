<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $this->seedUser1();
        $this->seedUser2();
    }

    private function seedUser1()
    {
        $userId = 1;
        $shiftStartTime = Carbon::createFromTimeString('07:00:00');
        $shiftEndTime = Carbon::createFromTimeString('15:00:00');
        $workHours = $shiftEndTime->diffInHours($shiftStartTime);

        $workDays = 0;
        $currentDate = Carbon::now()->startOfMonth();

        while ($workDays < 25) {
            if (!$currentDate->isWeekend()) {
                Attendance::updateOrCreate(
                    [
                        'id_user' => $userId,
                        'tanggal' => $currentDate->toDateString(),
                    ],
                    [
                        'status' => true,
                        'hari_kerja' => $workHours / 8,
                        'jumlah_jam_lembur' => 0,
                        'is_tanggal_merah' => false,
                    ]
                );
                $workDays++;
            }
            $currentDate->addDay();
        }
    }

    private function seedUser2()
    {
        $userId = 2;
        $shiftStartTime = Carbon::createFromTimeString('15:00:00');
        $shiftEndTime = Carbon::createFromTimeString('23:00:00');
        $workHours = $shiftEndTime->diffInHours($shiftStartTime);

        $currentDate = Carbon::now()->startOfMonth()->addWeek(); // Start a week into the month

        // Seed 5 holiday working days with random overtime
        for ($i = 0; $i < 5; $i++) {
            $randomOvertime = rand(2, 4);

            // Ensure no duplicate entry by using updateOrCreate
            Attendance::updateOrCreate(
                [
                    'id_user' => $userId,
                    'tanggal' => $currentDate->toDateString(),
                ],
                [
                    'status' => true,
                    'hari_kerja' => $workHours / 8,
                    'jumlah_jam_lembur' => $randomOvertime,
                    'is_tanggal_merah' => true,
                ]
            );

            // Move to the next working day (skip weekends)
            do {
                $currentDate->addDay();
            } while ($currentDate->isWeekend());
        }

        // Seed 20 regular working days on her shift (no overtime or holidays)
        $regularWorkDays = 0;
        $currentDate = Carbon::now()->startOfMonth(); // Reset to the start of the month

        while ($regularWorkDays < 20) {
            if (!$currentDate->isWeekend() && !$this->isHoliday($currentDate)) {
                Attendance::updateOrCreate(
                    [
                        'id_user' => $userId,
                        'tanggal' => $currentDate->toDateString(),
                    ],
                    [
                        'status' => true,
                        'hari_kerja' => $workHours / 8,
                        'jumlah_jam_lembur' => 0,
                        'is_tanggal_merah' => false,
                    ]
                );
                $regularWorkDays++;
            }
            $currentDate->addDay();
        }
    }

    // Helper function to simulate holidays
    private function isHoliday(Carbon $date)
    {
        $holidays = [
            Carbon::now()->startOfMonth()->addDays(10)->toDateString(), // Example holiday
            Carbon::now()->startOfMonth()->addDays(15)->toDateString(), // Example holiday
        ];
        return in_array($date->toDateString(), $holidays);
    }
}
