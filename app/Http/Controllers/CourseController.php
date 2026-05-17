<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Gelanggang; // Wajib tambah ni untuk tarik data Gelanggang
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
            
            // Tarik sekali data Gelanggang & Instructor supaya tak error kat jadual
            $courses = Course::with(['instructor', 'gelanggang'])->where('instructor_ID', $instructorId)->get();
        } 
        // Jika Staff (Admin) atau Super Admin yang login, tunjuk semua kursus
        else {
            $courses = Course::with(['instructor', 'gelanggang'])->get();
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
    // FUNGSI UNTUK INSTRUCTOR SET JADUAL
    // ==========================================

    public function editSchedule($id)
    {
        $course = Course::findOrFail($id);
        $instructor = Auth::guard('instructor')->user();
        
        // Halang instructor lain atau staff dari ubah jadual yang bukan hak dia
        if (!Auth::guard('instructor')->check() || $course->instructor_ID != ($instructor->instructor_ID ?? $instructor->id)) {
            abort(403, 'Unauthorized action. You do not have permission to edit this schedule.');
        }

        // Cari SEMUA gelanggang yang Instructor ni ajar (dan yang dah di-approve oleh HQ)
        $gelanggangs = Gelanggang::where('instructor_ID', $course->instructor_ID)
                                 ->where('status', 'approved')
                                 ->get();

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

    // ==========================================
    // FUNGSI UNTUK LIHAT SENARAI PELAJAR
    // ==========================================
    public function enrolledStudents()
    {
        $instructorId = Auth::guard('instructor')->user()->instructor_ID ?? Auth::guard('instructor')->id();
        
        // Tarik kursus milik instructor ni, dan tarik sekali data pendaftaran (enrollments) berserta data User (pelajar)
        $courses = Course::with(['gelanggang', 'enrollments.user']) 
                         ->where('instructor_ID', $instructorId)
                         ->get();

        return view('instructor.enrolled', compact('courses'));
    }
}