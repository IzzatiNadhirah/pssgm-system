<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';
    
    // Assuming you follow your standard naming convention
    protected $primaryKey = 'course_ID'; 
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'course_code',
        'course_name',
        'description',
        'instructor_ID', // Links this course to an instructor
    ];

    // Relationship: A course belongs to an Instructor
    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_ID', 'instructor_ID');
    }
}