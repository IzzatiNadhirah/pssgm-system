<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        // Jika yang login adalah Instructor, tunjuk kursus dia sahaja
        if (Auth::guard('instructor')->check()) {
            $instructorId = Auth::guard('instructor')->user()->instructor_ID ?? Auth::guard('instructor')->id();
            
            // Tarik sekali data Instructor supaya tak error
            $courses = Course::with(['instructor'])->where('instructor_ID', $instructorId)->get();
        } 
        // Jika Staff (Admin) atau Super Admin yang login, tunjuk semua kursus
        else {
            $courses = Course::with(['instructor'])->get();
        }
        
        return view('course.index', compact('courses'));
    }

    public function create()
    {
        // Pastikan hanya staff/admin boleh akses create page
        if (!Auth::guard('staff')->check()) {
            abort(403, 'Unauthorized action. Only Admin Staff can register a course.');
        }

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
        // Pastikan hanya staff/admin boleh akses edit page
        if (!Auth::guard('staff')->check()) {
            abort(403, 'Unauthorized action. Only Admin Staff can edit a course.');
        }

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
        // Pastikan hanya staff/admin boleh padam kursus
        if (!Auth::guard('staff')->check()) {
            abort(403, 'Unauthorized action. Only Admin Staff can delete a course.');
        }

        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully!');
    }

    // ==========================================
    // FUNGSI UNTUK LIHAT SENARAI PELAJAR
    // ==========================================
    public function enrolledStudents()
    {
        $instructorId = Auth::guard('instructor')->user()->instructor_ID ?? Auth::guard('instructor')->id();
        
        // Tarik kursus milik instructor ni, dan tarik sekali data pendaftaran (enrollments) berserta data User (pelajar)
        // NOTA: 'gelanggang' telah dibuang dari 'with()' kerana ia tiada lagi dalam jadual courses
        $courses = Course::with(['enrollments.user']) 
                         ->where('instructor_ID', $instructorId)
                         ->get();

        return view('instructor.enrolled', compact('courses'));
    }
}