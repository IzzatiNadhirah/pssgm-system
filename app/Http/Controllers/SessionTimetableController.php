<?php

namespace App\Http\Controllers;

use App\Models\SessionTimetable;
use App\Models\Course;
use App\Models\Gelanggang; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimetableController extends Controller
{
    public function index()
    {
        $instructorId = Auth::guard('instructor')->user()->instructor_ID ?? Auth::guard('instructor')->id();

        // KEKALKAN variable $timetables macam kod asal awak
        $timetables = SessionTimetable::with(['course', 'gelanggang'])
            ->whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_ID', $instructorId);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // KEKALKAN nama folder session_timetable (takde 's')
        return view('session_timetable.index', compact('timetables'));
    }

    public function create()
    {
        $instructorId = Auth::guard('instructor')->user()->instructor_ID ?? Auth::guard('instructor')->id();

        $courses = Course::where('instructor_ID', $instructorId)->get();
        $gelanggangs = Gelanggang::where('instructor_ID', $instructorId)
                                 ->where('status', 'approved')
                                 ->get();

        // KEKALKAN nama folder session_timetable (takde 's')
        return view('session_timetable.create', compact('courses', 'gelanggangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_ID' => 'required',
            'gel_ID' => 'required', 
            'start_time' => 'required',
            'end_time' => 'required',
            'capacity' => 'required|integer|min:1',
        ]);

        // SIMPAN TERUS KE DALAM DATABASE (TANPA GABUNG MASA)
        SessionTimetable::create([
            'course_ID' => $request->course_ID,
            'gel_ID' => $request->gel_ID,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'capacity' => $request->capacity,
        ]);

        // Biarkan 'sessions.index' kat sini sebab ia berhubung dengan web.php
        return redirect()->route('sessions.index')->with('success', 'New class session schedule successfully created!');
    }

    public function edit($id)
    {
        $timetable = SessionTimetable::findOrFail($id);
        $instructorId = Auth::guard('instructor')->user()->instructor_ID ?? Auth::guard('instructor')->id();

        $courses = Course::where('instructor_ID', $instructorId)->get();
        $gelanggangs = Gelanggang::where('instructor_ID', $instructorId)
                                 ->where('status', 'approved')
                                 ->get();

        // KEKALKAN nama folder session_timetable (takde 's')
        return view('session_timetable.edit', compact('timetable', 'courses', 'gelanggangs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'course_ID' => 'required',
            'gel_ID' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'capacity' => 'required|integer|min:1',
        ]);

        $session = SessionTimetable::findOrFail($id);
        
        // UPDATE TERUS KE DALAM DATABASE
        $session->update([
            'course_ID' => $request->course_ID,
            'gel_ID' => $request->gel_ID,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'capacity' => $request->capacity,
        ]);

        // Biarkan 'sessions.index' kat sini
        return redirect()->route('sessions.index')->with('success', 'Class session updated successfully!');
    }

    public function destroy($id)
    {
        $session = SessionTimetable::findOrFail($id);
        $session->delete();

        // Biarkan 'sessions.index' kat sini
        return redirect()->route('sessions.index')->with('success', 'Session schedule deleted successfully.');
    }
}