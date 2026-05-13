<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionTimetable extends Model
{
    use HasFactory;

    // 1. Kenal pasti nama table
    protected $table = 'session_timetable';

    // 2. Disable default auto-increment sebab table ni guna composite primary key
    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'course_ID',
        'user_ID',
        'session_time',
        'capacity',
    ];

    // Hubungan dengan Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_ID', 'course_ID');
    }

    // Hubungan dengan User (Pelajar)
    public function student()
    {
        return $this->belongsTo(User::class, 'user_ID', 'user_ID');
    }
}