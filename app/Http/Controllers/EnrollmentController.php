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

        $session_ids = $request->input('session_ids');
        if (empty($session_ids) && $request->has('session_id')) {
            $session_ids = [$request->input('session_id')]; 
        }

        if (empty($session_ids)) {
            return redirect()->back()->with('error', 'Enrollment failed! Please select a valid class session date.');
        }

        $berjaya = 0;
        $gagalPenuh = 0;
        $gagalDuplicate = 0;

        foreach ($session_ids as $sid) {
            $session = SessionTimetable::find($sid);
            if (!$session) continue;

            // Check duplicate enrollment
            $alreadyEnrolled = Enrollment::where('user_ID', $user->user_ID)
                                         ->where('session_ID', $sid)
                                         ->exists();

            if ($alreadyEnrolled) {
                $gagalDuplicate++;
                continue; 
            }

            // Check class capacity
            $currentEnrolled = Enrollment::where('session_ID', $sid)->count();
            
            if ($session->capacity && $currentEnrolled >= $session->capacity) {
                $gagalPenuh++;
                continue; 
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

        if ($berjaya > 0) {
            $mesej = "Congratulations! You have successfully enrolled in $berjaya class session(s).";
            
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

        // KITA EJAS SINI: Buat array events berserta data pop-up modal
        $calendarEvents = [];
        foreach ($enrollments as $enrollment) {
            $sesi = $enrollment->session;
            
            if ($sesi) {
                // Tentukan adakah kelas dah lepas atau belum
                $tarikhMula = \Carbon\Carbon::parse($sesi->start_time)->format('Y-m-d');
                $masaTamat = $sesi->end_time;
                
                if (strlen($masaTamat) <= 8) {
                    $gabunganTamat = \Carbon\Carbon::parse($tarikhMula . ' ' . $masaTamat);
                } else {
                    $gabunganTamat = \Carbon\Carbon::parse($masaTamat);
                }
                
                $isPast = $gabunganTamat->isPast();

                $calendarEvents[] = [
                    'title' => $enrollment->course->course_type ?? 'Kelas Silat',
                    
                    // Format ISO8601 TEPAT tanpa '+08:00' supaya browser tak tambah 8 jam
                    'start' => \Carbon\Carbon::parse($sesi->start_time)->format('Y-m-d\TH:i:s'),
                    'end'   => $gabunganTamat->format('Y-m-d\TH:i:s'),
                    
                    // Warna kelabu kalau dah lepas, warna merah gayong kalau belum
                    'color' => $isPast ? '#888888' : '#cc0000',
                    'className' => $isPast ? 'fc-event-past' : '',
                    
                    // Maklumat tambahan untuk dipaparkan di dalam Pop-up Modal
                    'extendedProps' => [
                        'courseName'  => $enrollment->course->course_type ?? 'Training Class',
                        'instructor'  => $enrollment->course->instructor->name ?? 'TBA',
                        'location'    => $sesi->gelanggang->gel_name ?? 'TBA',
                        'timeDisplay' => \Carbon\Carbon::parse($sesi->start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($sesi->end_time)->format('h:i A')
                    ]
                ];
            }
        }

        // Hantar $calendarEvents ke fail paparan blade
        return view('user.timetable', compact('enrollments', 'calendarEvents'));
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