<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionTimetable extends Model
{
    use HasFactory;

    protected $table = 'session_timetable';

    // KITA BUANG $fillable DAN GANTI DENGAN $guarded
    // Maksudnya: "Jangan halang apa-apa data, benarkan semua masuk!"
    // Termasuklah column 'postponed_from_start' & 'postponed_from_end' yang baru bos tambah kat pgAdmin tu!
    protected $guarded = [];

    // Hubungan dengan Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_ID', 'course_ID');
    }

    // Hubungan dengan Gelanggang
    public function gelanggang()
    {
        return $this->belongsTo(Gelanggang::class, 'gel_ID', 'gel_ID');
    }

    // Hubungan dengan User (Pelajar)
    public function student()
    {
        return $this->belongsTo(User::class, 'user_ID', 'user_ID');
    }
}