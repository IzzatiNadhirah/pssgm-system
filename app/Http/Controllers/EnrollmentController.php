<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store($course_id)
    {
        $user = Auth::user();

        // 1. Cek kalau dia dah bayar membership ke belum
        if (is_null($user->membership)) {
            return redirect()->back()->with('error', 'Sila bayar yuran keahlian dulu sebelum Join Training!');
        }

        // Ambil data kursus dulu supaya kita boleh buat validation
        $course = Course::findOrFail($course_id);

        // 2. KUNCI KESELAMATAN: Pastikan kursus dah ada cikgu, tempat & masa
        if (empty($course->instructor_ID) || empty($course->gelanggang_ID) || empty($course->session_time)) {
            return redirect()->back()->with('error', 'Pendaftaran disekat! Kursus ini belum mempunyai jadual atau tenaga pengajar yang lengkap.');
        }

        // 3. Cek kalau dia dah pernah daftar kelas yang sama
        $alreadyEnrolled = Enrollment::where('user_ID', $user->user_ID)
                                     ->where('course_ID', $course_id)
                                     ->exists();

        if ($alreadyEnrolled) {
            return redirect()->back()->with('error', 'Awak dah pun berdaftar dalam kursus ini.');
        }

        // 4. Cek kapasiti kelas kalau penuh
        $currentEnrolled = Enrollment::where('course_ID', $course_id)->count();
        
        if ($course->capacity && $currentEnrolled >= $course->capacity) {
            return redirect()->back()->with('error', 'Minta maaf, kelas ini sudah penuh!');
        }

        // 5. Daftar masuk sistem!
        Enrollment::create([
            'user_ID' => $user->user_ID,
            'course_ID' => $course_id,
            'enroll_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Tahniah! Awak dah berjaya daftar kelas ' . $course->course_type);
    }

    public function myTimetable()
    {
        $user = Auth::user();

        // Cari semua pendaftaran (enrollment) milik user ni.
        // Kita "tarik" sekali maklumat course, cikgu, dan gelanggang supaya senang nak papar.
        $enrollments = Enrollment::with(['course.instructor', 'course.gelanggang'])
                                 ->where('user_ID', $user->user_ID)
                                 ->orderBy('created_at', 'desc')
                                 ->get();

        return view('user.timetable', compact('enrollments'));
    }
}