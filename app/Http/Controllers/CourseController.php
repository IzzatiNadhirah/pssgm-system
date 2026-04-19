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
        //
    }

    public function create()
    {
        // Fetch all instructors to populate the dropdown
        $instructors = Instructor::all(); 
        
        return view('course.create', compact('instructors'));
    }

    public function store(Request $request)
    {
        // 1. Validate the incoming form data
        $request->validate([
            'course_name' => 'required|string|max:255',
            'description' => 'required|string',
            'instructor_ID' => 'required',
        ]);

        // 2. Create the new course
        Course::create([
            'course_code' => 'CRS-' . strtoupper(Str::random(5)), // Auto-generate code
            'course_name' => $request->course_name,
            'description' => $request->description,
            'instructor_ID' => $request->instructor_ID,
        ]);

        // 3. Redirect back with a success message
        return redirect()->route('courses.create')->with('success', 'Course registered successfully!');
    }
    
    // ... leave other standard resource methods empty for now
}