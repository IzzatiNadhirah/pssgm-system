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
        'course_type', // Replaced course_name and description
        'instructor_ID',
    ];

    // 4. Connect the Course to the Instructor
    public function instructor()
    {
        // Adjust 'instructor_ID' if your foreign key is named differently
        return $this->belongsTo(Instructor::class, 'instructor_ID', 'instructor_ID');
    }
}