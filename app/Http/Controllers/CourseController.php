<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        // Fetch all courses. Assuming you have an 'instructor' relationship in your Course model.
        $courses = Course::with('instructor')->get();
        
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
}