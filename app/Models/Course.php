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
    ];
}