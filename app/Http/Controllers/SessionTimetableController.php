<?php

namespace App\Http\Controllers;

use App\Models\SessionTimetable;
use App\Models\Course;
use App\Models\Gelanggang; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // PENTING: Tambah Carbon untuk urus masa

class SessionTimetableController extends Controller
{
    // KITA EJAS BAHAGIAN NI: Tambah Request $request untuk tangkap ID dari URL
    public function index(Request $request)
    {
        $instructorId = Auth::guard('instructor')->user()->instructor_ID ?? Auth::guard('instructor')->id();

        // 1. Mulakan asas query (tarik semua jadual milik instructor ni)
        $query = SessionTimetable::with(['course', 'gelanggang'])
            ->whereHas('course', function($q) use ($instructorId) {
                $q->where('instructor_ID', $instructorId);
            });

        // 2. TAPISAN (FILTER): Kalau URL ada bawa course_id, kita tapis jadual tu
        if ($request->has('course_id')) {
            $query->where('course_ID', $request->course_id);
        }

        // 3. Susun dan dapatkan hasil
        $timetables = $query->orderBy('start_time', 'asc')->get();
        
        return view('session_timetable.index', compact('timetables'));
    }

    public function create()
    {
        $instructorId = Auth::guard('instructor')->user()->instructor_ID ?? Auth::guard('instructor')->id();

        $courses = Course::where('instructor_ID', $instructorId)->get();
        $gelanggangs = Gelanggang::where('instructor_ID', $instructorId)
                                 ->where('status', 'approved')
                                 ->get();

        return view('session_timetable.create', compact('courses', 'gelanggangs'));
    }

    public function store(Request $request)
    {
        // TAMBAH: after:now untuk pastikan tarikh/masa tak backdated
        $request->validate([
            'course_ID' => 'required',
            'gel_ID' => 'required', 
            'start_time' => 'required|date|after:now',
            'end_time' => 'required',
            'capacity' => 'required|integer|min:1',
        ], [
            'start_time.after' => 'Invalid Date: You cannot schedule a class in the past!'
        ]);

        // 1. GABUNGKAN MASA: Ambil tarikh dari start_time dan gabung dengan end_time
        $startDate = Carbon::parse($request->start_time)->format('Y-m-d');
        $startTime = Carbon::parse($request->start_time);
        $combinedEndTime = Carbon::parse($startDate . ' ' . $request->end_time);

        // 2. Semak logik masa: Masa tamat mesti selepas masa mula
        if ($combinedEndTime <= $startTime) {
            return back()->withErrors(['time_error' => 'Invalid Time: End Time must be after Start Time.'])->withInput();
        }

        $course = Course::findOrFail($request->course_ID);
        $instructorId = $course->instructor_ID;

        // 3. CLASH CHECK: Jadual Instructor Bertindih?
        // Menghalang Instructor set 2 waktu sama kat mana-mana gelanggang dia
        $instructorClash = SessionTimetable::whereHas('course', function($query) use ($instructorId) {
            $query->where('instructor_ID', $instructorId);
        })->where(function($query) use ($startTime, $combinedEndTime) {
            $query->where('start_time', '<', $combinedEndTime)
                  ->where('end_time', '>', $startTime);
        })->exists();

        if ($instructorClash) {
            return back()->withErrors(['clash_error' => 'Schedule Clash: You already have another class scheduled at one of your locations during this time slot!'])->withInput();
        }

        // 4. SIMPAN KE DALAM DATABASE
        SessionTimetable::create([
            'course_ID' => $request->course_ID,
            'gel_ID' => $request->gel_ID,
            'start_time' => $startTime,
            'end_time' => $combinedEndTime, // Simpan masa yang dah digabungkan
            'capacity' => $request->capacity,
        ]);

        // Berbalik ke halaman index dengan membawa course_id supaya dia kekal di page subjek yang sama
        return redirect()->route('sessions.index', ['course_id' => $request->course_ID])->with('success', 'New class session schedule successfully created!');
    }

    public function edit($id)
    {
        $timetable = SessionTimetable::findOrFail($id);
        $instructorId = Auth::guard('instructor')->user()->instructor_ID ?? Auth::guard('instructor')->id();

        $courses = Course::where('instructor_ID', $instructorId)->get();
        $gelanggangs = Gelanggang::where('instructor_ID', $instructorId)
                                 ->where('status', 'approved')
                                 ->get();

        return view('session_timetable.edit', compact('timetable', 'courses', 'gelanggangs'));
    }

    public function update(Request $request, $id)
    {
        // TAMBAH: after:now untuk pastikan masa update pun tak backdated
        $request->validate([
            'course_ID' => 'required',
            'gel_ID' => 'required',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required',
            'capacity' => 'required|integer|min:1',
        ], [
            'start_time.after' => 'Invalid Date: You cannot schedule a class in the past!'
        ]);

        $session = SessionTimetable::findOrFail($id);
        
        // 1. GABUNGKAN MASA
        $startDate = Carbon::parse($request->start_time)->format('Y-m-d');
        $startTime = Carbon::parse($request->start_time);
        $combinedEndTime = Carbon::parse($startDate . ' ' . $request->end_time);

        // 2. Semak logik masa
        if ($combinedEndTime <= $startTime) {
            return back()->withErrors(['time_error' => 'Invalid Time: End Time must be after Start Time.'])->withInput();
        }

        $course = Course::findOrFail($request->course_ID);
        $instructorId = $course->instructor_ID;

        // 3. CLASH CHECK: Instructor (Kecuali sesi yang sedang di-edit ini)
        $instructorClash = SessionTimetable::where($session->getKeyName(), '!=', $id) // Abaikan rekod yang tengah edit ni
            ->whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_ID', $instructorId);
            })->where(function($query) use ($startTime, $combinedEndTime) {
                $query->where('start_time', '<', $combinedEndTime)
                      ->where('end_time', '>', $startTime);
            })->exists();

        if ($instructorClash) {
            return back()->withErrors(['clash_error' => 'Schedule Clash: You already have another class scheduled at one of your locations during this time slot!'])->withInput();
        }

        // 4. UPDATE TERUS KE DALAM DATABASE
        $session->update([
            'course_ID' => $request->course_ID,
            'gel_ID' => $request->gel_ID,
            'start_time' => $startTime,
            'end_time' => $combinedEndTime, // Simpan masa yang dah digabungkan
            'capacity' => $request->capacity,
        ]);

        // Berbalik ke halaman index dengan membawa course_id supaya dia kekal di page subjek yang sama
        return redirect()->route('sessions.index', ['course_id' => $request->course_ID])->with('success', 'Class session updated successfully!');
    }

    public function destroy($id)
    {
        $session = SessionTimetable::findOrFail($id);
        $course_id = $session->course_ID; // Simpan course_id sebelum delete
        $session->delete();

        // Lepas delete, terus patah balik ke page jadual subjek tu
        return redirect()->route('sessions.index', ['course_id' => $course_id])->with('success', 'Session schedule deleted successfully.');
    }
}