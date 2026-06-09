<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Cawangan; // KITA EJAS SINI: Wajib import model Cawangan
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        // 1. Jika yang login adalah Instructor
        if (Auth::guard('instructor')->check()) {
            $instructorId = Auth::guard('instructor')->user()->instructor_ID ?? Auth::guard('instructor')->id();
            $courses = Course::with(['instructor'])->where('instructor_ID', $instructorId)->get();
        } 
        // 2. Jika yang login adalah Staff (Admin Cawangan)
        elseif (Auth::guard('staff')->check()) {
            $staff = Auth::guard('staff')->user();
            $staffId = $staff->staff_ID ?? $staff->id; // Ambil ID staf

            // Langkah 1: Cari Cawangan mana yang Staf ni jaga
            $cawangan = Cawangan::where('staff_ID', $staffId)->first();

            if ($cawangan) {
                // Langkah 2: Jika staf ada cawangan, tapis kursus ikut caw_ID tu
                $courses = Course::whereHas('instructor', function($query) use ($cawangan) {
                    $query->where('caw_ID', $cawangan->caw_ID);
                })->with(['instructor'])->get();
            } else {
                // Jika staf ni belum di-assign ke mana-mana cawangan, bagi array kosong
                $courses = collect(); 
            }
        } 
        // 3. Jika Super Admin yang login, tunjuk semua kursus
        else {
            $courses = Course::with(['instructor'])->get();
        }
        
        return view('course.index', compact('courses'));
    }

    public function create()
    {
        if (!Auth::guard('staff')->check()) {
            abort(403, 'Unauthorized action. Only Admin Staff can register a course.');
        }

        $staff = Auth::guard('staff')->user();
        $staffId = $staff->staff_ID ?? $staff->id;
        
        // Cari Cawangan Staf
        $cawangan = Cawangan::where('staff_ID', $staffId)->first();

        // Tapis dropdown supaya keluar Instructor cawangan dia je
        if ($cawangan) {
            $instructors = Instructor::where('caw_ID', $cawangan->caw_ID)->get();
        } else {
            $instructors = collect();
        }
        
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
        if (!Auth::guard('staff')->check()) {
            abort(403, 'Unauthorized action. Only Admin Staff can edit a course.');
        }

        $course = Course::findOrFail($id);
        
        $staff = Auth::guard('staff')->user();
        $staffId = $staff->staff_ID ?? $staff->id;
        
        // Cari Cawangan Staf
        $cawangan = Cawangan::where('staff_ID', $staffId)->first();

        // Tapis dropdown instructor untuk edit
        if ($cawangan) {
            $instructors = Instructor::where('caw_ID', $cawangan->caw_ID)->get();
        } else {
            $instructors = collect();
        }
        
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
        
        $courses = Course::with(['enrollments.user']) 
                         ->where('instructor_ID', $instructorId)
                         ->get();

        return view('instructor.enrolled', compact('courses'));
    }
}