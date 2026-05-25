<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\SessionTimetable; // PENTING: Import Model baru ni
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store($course_id)
    {
        $user = Auth::user();

        // 1. Check membership payment
        if (is_null($user->membership)) {
            return redirect()->back()->with('error', 'Please pay the membership fee before joining the training!');
        }

        // Fetch course data
        $course = Course::findOrFail($course_id);

        // 2. SAFETY LOCK: Check if there is an active session in SessionTimetable
        // Kita cari kalau kursus ni dah ada sesi berjadual (Masa & Tempat)
        $session = SessionTimetable::where('course_ID', $course_id)->first();

        if (empty($course->instructor_ID) || empty($session)) {
            return redirect()->back()->with('error', 'Enrollment blocked! This course does not have a complete schedule or assigned instructor yet.');
        }

        // 3. Check for duplicate enrollment
        $alreadyEnrolled = Enrollment::where('user_ID', $user->user_ID)
                                     ->where('course_ID', $course_id)
                                     ->exists();

        if ($alreadyEnrolled) {
            return redirect()->back()->with('error', 'You are already enrolled in this course.');
        }

        // 4. Check class capacity (Guna kapasiti dari SessionTimetable)
        $currentEnrolled = Enrollment::where('course_ID', $course_id)->count();
        
        if ($session->capacity && $currentEnrolled >= $session->capacity) {
            return redirect()->back()->with('error', 'Sorry, this class is already full!');
        }

        // 5. Process enrollment
        Enrollment::create([
            'user_ID' => $user->user_ID,
            'course_ID' => $course_id,
            'enroll_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Congratulations! You have successfully enrolled in ' . $course->course_type);
    }

    public function myTimetable()
    {
        $user = Auth::user();

        // Di sini kita tarik hubungan melalui course ke gelanggang dan sesi
        $enrollments = Enrollment::with(['course.instructor', 'course.gelanggang'])
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