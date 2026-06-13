<?php

namespace App\Http\Controllers;

use App\Models\SessionTimetable;
use App\Models\Course;
use App\Models\Gelanggang; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SessionTimetableController extends Controller
{
    public function index(Request $request)
    {
        $instructorId = Auth::guard('instructor')->user()->instructor_ID ?? Auth::guard('instructor')->id();

        $query = SessionTimetable::with(['course', 'gelanggang'])
            ->whereHas('course', function($q) use ($instructorId) {
                $q->where('instructor_ID', $instructorId);
            });

        if ($request->has('course_id')) {
            $query->where('course_ID', $request->course_id);
        }

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
        // 1. KITA EJAS SINI: Tambah validation untuk tarikh berulang
        $request->validate([
            'course_ID' => 'required',
            'gel_ID' => 'required', 
            'start_time' => 'required|date|after:now',
            'end_time' => 'required',
            'capacity' => 'required|integer|min:1',
            'repeat_until' => 'nullable|date|after:start_time',
        ], [
            'start_time.after' => 'Invalid Date: You cannot schedule a class in the past!',
            'repeat_until.after' => 'Repeat Until date must be after the Start Time.'
        ]);

        $baseStartTime = Carbon::parse($request->start_time);
        $startDateStr = $baseStartTime->format('Y-m-d');
        $baseEndTime = Carbon::parse($startDateStr . ' ' . $request->end_time);

        if ($baseEndTime <= $baseStartTime) {
            return back()->withErrors(['time_error' => 'Invalid Time: End Time must be after Start Time.'])->withInput();
        }

        $course = Course::findOrFail($request->course_ID);
        $instructorId = $course->instructor_ID;

        // 2. KITA EJAS SINI: Tetapkan logik pusingan (Looping)
        $isRecurring = $request->has('repeat_weekly') && $request->filled('repeat_until');
        $repeatUntilDate = $isRecurring ? Carbon::parse($request->repeat_until)->endOfDay() : $baseStartTime->copy()->endOfDay();

        $sessionsToCreate = []; // Simpan data sementara sebelum masuk ke database

        $allInstructorSessions = SessionTimetable::whereHas('course', function($query) use ($instructorId) {
            $query->where('instructor_ID', $instructorId);
        })->with('course')->get();

        $currentStartTime = $baseStartTime->copy();
        $currentEndTime = $baseEndTime->copy();

        // 3. KITA EJAS SINI: Loop selagi tarikh semasa belum melepasi tarikh Repeat Until
        while ($currentStartTime <= $repeatUntilDate) {
            
            // Semak Clash untuk minggu ini
            $clashingSession = $allInstructorSessions->first(function($sesi) use ($currentStartTime, $currentEndTime) {
                $dbStart = Carbon::parse($sesi->start_time);
                
                $dbEndString = $sesi->end_time;
                if (strlen($dbEndString) <= 8) { 
                    $dbEnd = Carbon::parse($dbStart->format('Y-m-d') . ' ' . $dbEndString);
                } else {
                    $dbEnd = Carbon::parse($dbEndString);
                }

                return ($dbStart < $currentEndTime) && ($dbEnd > $currentStartTime);
            });

            if ($clashingSession) {
                $namaKursus = $clashingSession->course->course_type ?? 'Kursus Lain';
                $tarikhClash = Carbon::parse($clashingSession->start_time)->format('d M Y (h:i A)');
                $attemptedDate = $currentStartTime->format('d M Y');
                
                return back()->withErrors([
                    'clash_error' => "Schedule Conflict! Attempted to schedule on $attemptedDate, but you already have '$namaKursus' on $tarikhClash."
                ])->withInput();
            }

            // Simpan data minggu ini ke dalam array (belum masuk database lagi)
            $sessionsToCreate[] = [
                'course_ID' => $request->course_ID,
                'gel_ID' => $request->gel_ID,
                'start_time' => $currentStartTime->copy(),
                'end_time' => $currentEndTime->copy(),
                'capacity' => $request->capacity,
            ];

            // Kalau bukan kelas berulang, hentikan loop
            if (!$isRecurring) break;

            // Tambah 7 hari untuk minggu seterusnya
            $currentStartTime->addWeek();
            $currentEndTime->addWeek();
        }

        // 4. KITA EJAS SINI: Kalau semua lulus (tiada clash), barulah kita masukkan semua sekaligus
        foreach ($sessionsToCreate as $data) {
            SessionTimetable::create($data);
        }

        $successMessage = $isRecurring ? count($sessionsToCreate) . ' weekly class sessions successfully created!' : 'New class session schedule successfully created!';

        return redirect()->route('sessions.index', ['course_id' => $request->course_ID])->with('success', $successMessage);
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
        
        $startDate = Carbon::parse($request->start_time)->format('Y-m-d');
        $startTime = Carbon::parse($request->start_time);
        $combinedEndTime = Carbon::parse($startDate . ' ' . $request->end_time);

        if ($combinedEndTime <= $startTime) {
            return back()->withErrors(['time_error' => 'Invalid Time: End Time must be after Start Time.'])->withInput();
        }

        $course = Course::findOrFail($request->course_ID);
        $instructorId = $course->instructor_ID;

        // --- CLASH CHECK DETEKTIF ---
        $allInstructorSessions = SessionTimetable::where($session->getKeyName(), '!=', $id) 
            ->whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_ID', $instructorId);
            })->with('course')->get();

        $clashingSession = $allInstructorSessions->first(function($sesi) use ($startTime, $combinedEndTime) {
            $dbStart = Carbon::parse($sesi->start_time);
            
            $dbEndString = $sesi->end_time;
            if (strlen($dbEndString) <= 8) { 
                $dbEnd = Carbon::parse($dbStart->format('Y-m-d') . ' ' . $dbEndString);
            } else {
                $dbEnd = Carbon::parse($dbEndString);
            }

            return ($dbStart < $combinedEndTime) && ($dbEnd > $startTime);
        });

        if ($clashingSession) {
            $namaKursus = $clashingSession->course->course_type ?? 'Kursus Lain';
            $tarikhClash = Carbon::parse($clashingSession->start_time)->format('d M Y (h:i A)');
            return back()->withErrors(['clash_error' => "Jadual Bertembung! Bos dah ada jadual '$namaKursus' pada tarikh $tarikhClash."])->withInput();
        }

        $dataToUpdate = [
            'course_ID' => $request->course_ID,
            'gel_ID' => $request->gel_ID,
            'start_time' => $startTime,
            'end_time' => $combinedEndTime,
            'capacity' => $request->capacity,
        ];

        // --- FIX ERROR 500: PAKSA DATABASE TERIMA FORMAT TIMESTAMP PENUH ---
        $oldStartTime = Carbon::parse($session->start_time);
        
        $dbEndString = $session->end_time;
        if (strlen($dbEndString) <= 8) { 
            $oldEndTime = Carbon::parse($oldStartTime->format('Y-m-d') . ' ' . $dbEndString);
        } else {
            $oldEndTime = Carbon::parse($dbEndString);
        }

        if ($oldStartTime->ne($startTime) || $oldEndTime->ne($combinedEndTime)) {
            $dataToUpdate['postponed_from_start'] = $oldStartTime->format('Y-m-d H:i:s');
            $dataToUpdate['postponed_from_end'] = $oldEndTime->format('Y-m-d H:i:s');
        }

        $session->update($dataToUpdate);

        return redirect()->route('sessions.index', ['course_id' => $request->course_ID])->with('success', 'Class session updated successfully!');
    }

    public function destroy($id)
    {
        $session = SessionTimetable::findOrFail($id);
        $course_id = $session->course_ID;
        $session->delete();

        return redirect()->route('sessions.index', ['course_id' => $course_id])->with('success', 'Session schedule deleted successfully.');
    }
}