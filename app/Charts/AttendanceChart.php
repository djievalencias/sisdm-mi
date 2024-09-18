<?php

declare(strict_types=1);

namespace App\Charts;

use App\Models\Attendance;
use App\Models\User;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class AttendanceChart extends Chart
{
    /**
     * Initializes the chart.
     */
    public function __construct()
    {
        parent::__construct();
        $this->labels(['Today']);
        $this->dataset('In', 'bar', [Attendance::countAttendance(false)]);
        $this->dataset('Out', 'bar', [Attendance::countAttendance(true)]);
        $this->dataset('Total User', 'line', [User::where('is_admin', false)->count()]);
    }
}
