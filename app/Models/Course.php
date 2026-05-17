<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'course';
    protected $primaryKey = 'course_ID';

    protected $fillable = [
        'course_code',
        'course_type',
        'instructor_ID',
        'session_time',
        'capacity',
        'gel_ID',
    ];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_ID', 'instructor_ID');
    }

    public function gelanggang()
    {
        return $this->belongsTo(Gelanggang::class, 'gel_ID', 'gel_ID');
    }

    // --- TAMBAH FUNGSI NI ---
    public function enrollments()
    {
        // Hubungkan Course dengan Enrollment guna 'course_ID'
        return $this->hasMany(Enrollment::class, 'course_ID', 'course_ID');
    }
}