<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $primaryKey = 'enroll_ID';

    // 1. KITA EJAS SINI: Tambah 'session_ID' supaya sistem benarkan data ni masuk database
    protected $fillable = [
        'user_ID',
        'course_ID',
        'session_ID',
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

    // 2. KITA EJAS SINI: Hubungan dengan table SessionTimetable
    public function session()
    {
        // Nota: Kalau Primary Key dalam jadual session bos ejaannya lain (cth: 'session_ID'), tukar 'id' kat hujung tu. 
        // Berdasarkan code bos yang sebelum ni, primary key dia nampak macam 'id'.
        return $this->belongsTo(SessionTimetable::class, 'session_ID', 'id');
    }
}