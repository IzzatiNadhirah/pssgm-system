<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // 1. Tell Laravel the exact name of your PostgreSQL table
    protected $table = 'course';

    // 2. Tell Laravel your custom primary key
    protected $primaryKey = 'course_ID';

    // 3. Allow mass assignment for these fields
    protected $fillable = [
        'course_code',
        'course_type',
        'instructor_ID',
        'session_time',
        'capacity',
        'gel_ID', // <--- Tambah ni
    ];

    // 4. Connect the Course to the Instructor
    public function instructor()
    {
        // Adjust 'instructor_ID' if your foreign key is named differently
        return $this->belongsTo(Instructor::class, 'instructor_ID', 'instructor_ID');
    }

    public function gelanggang()
    {
        return $this->belongsTo(Gelanggang::class, 'gel_ID', 'gel_ID');
    }
}