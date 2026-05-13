<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth; // Wajib tambah ni untuk guna fungsi login check

class CourseController extends Controller
{
    public function index()
    {
        // Jika yang login adalah Instructor, tunjuk kursus dia sahaja
        if (Auth::guard('instructor')->check()) {
            $instructorId = Auth::guard('instructor')->user()->instructor_ID;
            $courses = Course::with('instructor')->where('instructor_ID', $instructorId)->get();
        } 
        // Jika Staff (Admin) yang login, tunjuk semua kursus
        else {
            $courses = Course::with('instructor')->get();
        }
        
        return view('course.index', compact('courses'));
    }

    public function create()
    {
        // Fetch all instructors to populate the dropdown
        $instructors = Instructor::all(); 
        
        return view('course.create', compact('instructors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_type' => 'required|string|max:255',
            'instructor_ID' => 'required',
        ]);

        Course::create([
            'course_code' => 'CRS-' . strtoupper(Str::random(5)), 
            'course_type' => $request->course_type,
            'instructor_ID' => $request->instructor_ID,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course registered successfully!');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $instructors = Instructor::all();
        
        return view('course.edit', compact('course', 'instructors'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'course_type' => 'required|string|max:255',
            'instructor_ID' => 'required',
        ]);

        $course = Course::findOrFail($id);
        
        $course->update([
            'course_type' => $request->course_type,
            'instructor_ID' => $request->instructor_ID,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course updated successfully!');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully!');
    }

    // Memaparkan borang set jadual
    public function editSchedule($id)
    {
        $course = Course::findOrFail($id);
        
        if (Auth::guard('instructor')->check() && $course->instructor_ID != Auth::guard('instructor')->user()->instructor_ID) {
            abort(403, 'Anda tiada kebenaran untuk ubah jadual kursus ini.');
        }

        // Cari SEMUA gelanggang yang Instructor ni ajar
        $gelanggangs = \App\Models\Gelanggang::where('instructor_ID', $course->instructor_ID)->get();

        return view('course.schedule', compact('course', 'gelanggangs'));
    }

    public function updateSchedule(Request $request, $id)
    {
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'gel_ID' => 'required',
            'capacity' => 'required|integer|min:1',
        ]);

        $course = Course::findOrFail($id);
        
        // Formatkan masa menggunakan fungsi bawaan Laravel (Carbon)
        $start = \Carbon\Carbon::parse($request->start_time)->format('d M Y, h:i A');
        $end = \Carbon\Carbon::parse($request->end_time)->format('h:i A');
        $gabungan_masa = $start . ' - ' . $end;

        $course->update([
            'session_time' => $gabungan_masa,
            'capacity' => $request->capacity,
            'gel_ID' => $request->gel_ID,
        ]);

        return redirect()->route('courses.index')->with('success', 'Class schedule updated successfully!');
    }
}