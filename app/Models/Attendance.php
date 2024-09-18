<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at'];

    // Define the accessors to format date attributes
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Seoul')->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Seoul')->format('Y-m-d H:i:s');
    }

    public function detail()
    {
        return $this->hasMany(AttendanceDetail::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
