<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\SessionTimetable; 
use Illuminate\Http\Request; // Tambah Request untuk baca session_id
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    // KITA EJAS: Tambah Request $request kat sini
    public function store(Request $request, $course_id)
    {
        $user = Auth::user();

        // 1. Check membership payment
        if (is_null($user->membership)) {
            return redirect()->back()->with('error', 'Please pay the membership fee before joining the training!');
        }

        // Fetch course data
        $course = Course::findOrFail($course_id);

        // 2. KITA EJAS: Baca Session ID yang spesifik dihantar dari butang Enroll
        $session_id = $request->input('session_id');
        
        if (!$session_id) {
            return redirect()->back()->with('error', 'Enrollment failed! Please select a valid class session date.');
        }

        $session = SessionTimetable::findOrFail($session_id);

        if (empty($course->instructor_ID)) {
            return redirect()->back()->with('error', 'Enrollment blocked! This course does not have an assigned instructor yet.');
        }

        // 3. KITA EJAS: Check duplicate enrollment berdasarkan SESSION_ID, bukan lagi course_id
        $alreadyEnrolled = Enrollment::where('user_ID', $user->user_ID)
                                     ->where('session_ID', $session_id)
                                     ->exists();

        if ($alreadyEnrolled) {
            return redirect()->back()->with('error', 'You are already enrolled for this specific date and time.');
        }

        // 4. KITA EJAS: Check class capacity berdasarkan SESSION_ID
        $currentEnrolled = Enrollment::where('session_ID', $session_id)->count();
        
        if ($session->capacity && $currentEnrolled >= $session->capacity) {
            return redirect()->back()->with('error', 'Sorry, this specific class session is already full!');
        }

        // 5. KITA EJAS: Simpan data pendaftaran berserta session_ID
        Enrollment::create([
            'user_ID' => $user->user_ID,
            'course_ID' => $course_id,
            'session_ID' => $session_id, // <--- Wajib simpan session_ID kat database!
            'enroll_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Congratulations! You have successfully enrolled for the class on ' . \Carbon\Carbon::parse($session->start_time)->format('d M Y'));
    }

    public function myTimetable()
    {
        $user = Auth::user();

        // KITA EJAS: Kena tarik data 'session' juga sebab jadual dah dipindahkan ke sana
        $enrollments = Enrollment::with(['course.instructor', 'session.gelanggang.cawangan'])
                                 ->where('user_ID', $user->user_ID)
                                 ->orderBy('created_at', 'desc')
                                 ->get();

        return view('user.timetable', compact('enrollments'));
    }

    public function destroy($id)
    {
        $enrollment = Enrollment::where('enroll_ID', $id)
                                ->where('user_ID', Auth::user()->user_ID)
                                ->firstOrFail();

        $enrollment->delete();

        return redirect()->back()->with('success', 'You have successfully dropped the class.');
    }
}