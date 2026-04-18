<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionTimetable extends Model
{
    use HasFactory;

    protected $table = 'session_timetable';
    
    // Disable primary key auto-increment parameters for bridge tables
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'course_ID',
        'user_ID',
        'session_time',
        'capacity',
    ];
}