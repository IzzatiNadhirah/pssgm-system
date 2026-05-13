<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $primaryKey = 'enroll_ID';

    protected $fillable = [
        'user_ID',
        'course_ID',
        'enroll_date',
    ];

    // Hubungan dengan table User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID', 'user_ID');
    }

    // Hubungan dengan table Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_ID', 'course_ID');
    }
}