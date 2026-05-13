<?php

namespace App\Http\Controllers;

use App\Models\SessionTimetable;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimetableController extends Controller
{
    public function index()
    {
        // Dapatkan ID instructor yang sedang login
        $instructorId = Auth::guard('instructor')->user()->instructor_ID;

        // Dapatkan semua rekod timetable untuk kursus di bawah instructor ini
        $timetables = SessionTimetable::with(['course', 'student'])
            ->whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_ID', $instructorId);
            })->get();
        
        return view('session_timetable.index', compact('timetables'));
    }

    public function create()
    {
        $instructorId = Auth::guard('instructor')->user()->instructor_ID;

        // Instructor hanya boleh pilih kursus dia sendiri
        $courses = Course::where('instructor_ID', $instructorId)->get();
        
        // Senarai semua pelajar dalam sistem
        $students = User::all();

        return view('session_timetable.create', compact('courses', 'students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_ID' => 'required',
            'user_ID' => 'required',
            'session_time' => 'required|string',
            'capacity' => 'required|integer|min:1',
        ]);

        // Semak kalau pelajar dah daftar kursus yang sama untuk elak duplicate error
        $exists = SessionTimetable::where('course_ID', $request->course_ID)
                                  ->where('user_ID', $request->user_ID)
                                  ->first();

        if ($exists) {
            return back()->withErrors(['Pelajar ini sudah didaftarkan ke dalam kursus tersebut.']);
        }

        SessionTimetable::create([
            'course_ID' => $request->course_ID,
            'user_ID' => $request->user_ID,
            'session_time' => $request->session_time,
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('sessions.index')->with('success', 'Student successfully enrolled in the session!');
    }

    // Untuk fungsi Delete, sebab guna composite key, kita delete berdasarkan course_ID dan user_ID
    public function destroy($course_id, $user_id)
    {
        SessionTimetable::where('course_ID', $course_id)
                        ->where('user_ID', $user_id)
                        ->delete();

        return redirect()->route('sessions.index')->with('success', 'Enrollment record deleted successfully.');
    }
}