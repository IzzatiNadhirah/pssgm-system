<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\SessionTimetable; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store(Request $request, $course_id)
    {
        $user = Auth::user();

        // 1. Check membership payment
        if (is_null($user->membership)) {
            return redirect()->back()->with('error', 'Please pay the membership fee before joining the training!');
        }

        // Fetch course data
        $course = Course::findOrFail($course_id);

        if (empty($course->instructor_ID)) {
            return redirect()->back()->with('error', 'Enrollment blocked! This course does not have an assigned instructor yet.');
        }

        // 2. KITA EJAS SINI: Tangkap array 'session_ids' (pukal) atau 'session_id' (single)
        $session_ids = $request->input('session_ids');
        if (empty($session_ids) && $request->has('session_id')) {
            $session_ids = [$request->input('session_id')]; // Jadikan array juga supaya mudah loop
        }

        if (empty($session_ids)) {
            return redirect()->back()->with('error', 'Enrollment failed! Please select a valid class session date.');
        }

        // Pembolehubah untuk simpan rekod kejayaan/kegagalan
        $berjaya = 0;
        $gagalPenuh = 0;
        $gagalDuplicate = 0;

        // 3. KITA EJAS SINI: Loop pendaftaran untuk setiap ID Sesi yang dihantar
        foreach ($session_ids as $sid) {
            $session = SessionTimetable::find($sid);
            if (!$session) continue;

            // Check duplicate enrollment
            $alreadyEnrolled = Enrollment::where('user_ID', $user->user_ID)
                                         ->where('session_ID', $sid)
                                         ->exists();

            if ($alreadyEnrolled) {
                $gagalDuplicate++;
                continue; // Skip kelas ni, pergi kelas seterusnya
            }

            // Check class capacity
            $currentEnrolled = Enrollment::where('session_ID', $sid)->count();
            
            if ($session->capacity && $currentEnrolled >= $session->capacity) {
                $gagalPenuh++;
                continue; // Skip kelas ni, pergi kelas seterusnya
            }

            // Simpan data pendaftaran
            Enrollment::create([
                'user_ID' => $user->user_ID,
                'course_ID' => $course_id,
                'session_ID' => $sid,
                'enroll_date' => now(),
            ]);
            
            $berjaya++;
        }

        // 4. KITA EJAS SINI: Papar mesej berdasarkan hasil Loop tadi
        if ($berjaya > 0) {
            $mesej = "Congratulations! You have successfully enrolled in $berjaya class session(s).";
            
            // Beritahu juga kalau ada kelas yang ter-skip
            if ($gagalPenuh > 0 || $gagalDuplicate > 0) {
                $mesej .= " (Skipped " . ($gagalPenuh + $gagalDuplicate) . " session(s) due to being full or already enrolled).";
            }
            return redirect()->back()->with('success', $mesej);
        } else {
            return redirect()->back()->with('error', 'Enrollment failed! All selected sessions were either full or you have already enrolled in them.');
        }
    }

    public function myTimetable()
    {
        $user = Auth::user();

        $enrollments = Enrollment::with(['course.instructor', 'session.gelanggang.cawangan'])
                                 ->where('user_ID', $user->user_ID)
                                 ->orderBy('created_at', 'desc')
                                 ->get();

        return view('user.timetable', compact('enrollments'));
    }

    public function destroy($id)
    {
        $enrollment = Enrollment::where('enroll_ID', $id)
                                ->where('user_ID', Auth::user()->user_ID)
                                ->firstOrFail();

        $enrollment->delete();

        return redirect()->back()->with('success', 'You have successfully dropped the class.');
    }
}