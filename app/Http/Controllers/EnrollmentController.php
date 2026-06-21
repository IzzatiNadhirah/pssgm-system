<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\SessionTimetable; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Wajib import Carbon

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
        $gagalClash = 0; // Pembolehubah baru untuk log kelas yang clash

        // KITA EJAS SINI: Tarik siap-siap semua tarikh kelas yang pelajar ni DAH enroll
        $myEnrolledSessionIds = Enrollment::where('user_ID', $user->user_ID)->pluck('session_ID');
        $myEnrolledDates = [];
        
        if ($myEnrolledSessionIds->isNotEmpty()) {
            $myEnrolledDates = SessionTimetable::whereIn('id', $myEnrolledSessionIds)
                ->pluck('start_time')
                ->map(function($date) {
                    return Carbon::parse($date)->format('Y-m-d');
                })->toArray();
        }

        foreach ($session_ids as $sid) {
            $session = SessionTimetable::find($sid);
            if (!$session) continue;

            // 1. Semak jika pengguna ini cuba enroll kelas pada tarikh yang dah berlalu (Elak cheat inspect element)
            if (Carbon::parse($session->start_time)->isPast()) {
                continue; 
            }

            // 2. Check duplicate enrollment (Kelas yang SAMA tepat)
            $alreadyEnrolled = Enrollment::where('user_ID', $user->user_ID)
                                         ->where('session_ID', $sid)
                                         ->exists();

            if ($alreadyEnrolled) {
                $gagalDuplicate++;
                continue; 
            }

            // 3. KITA EJAS SINI: Semak pertindihan Tarikh (Clash)
            // Walaupun kelas berbeza, jika hari yang sama, sistem tolak!
            $tarikhSesiBaru = Carbon::parse($session->start_time)->format('Y-m-d');
            if (in_array($tarikhSesiBaru, $myEnrolledDates)) {
                $gagalClash++;
                continue;
            }

            // 4. Check class capacity
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
            // Tambah tarikh yang baru berjaya enroll ni ke dalam array supaya tak clash dengan loop seterusnya
            $myEnrolledDates[] = $tarikhSesiBaru;
        }

        if ($berjaya > 0) {
            $mesej = "Congratulations! You have successfully enrolled in $berjaya class session(s).";
            
            $isuGagal = [];
            if ($gagalPenuh > 0) $isuGagal[] = "$gagalPenuh full";
            if ($gagalDuplicate > 0) $isuGagal[] = "$gagalDuplicate duplicated";
            if ($gagalClash > 0) $isuGagal[] = "$gagalClash clashed with your existing schedule";
            
            if (count($isuGagal) > 0) {
                $mesej .= " (Skipped " . implode(', ', $isuGagal) . ").";
            }
            return redirect()->back()->with('success', $mesej);
        } else {
            return redirect()->back()->with('error', 'Enrollment failed! All selected sessions were either full, already enrolled, or clash with your existing schedule.');
        }
    }

    public function myTimetable()
    {
        $user = Auth::user();

        $enrollments = Enrollment::with(['course.instructor', 'session.gelanggang.cawangan'])
                                 ->where('user_ID', $user->user_ID)
                                 ->orderBy('created_at', 'desc')
                                 ->get();

        $calendarEvents = [];
        foreach ($enrollments as $enrollment) {
            $sesi = $enrollment->session;
            
            if ($sesi) {
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
                    
                    'start' => \Carbon\Carbon::parse($sesi->start_time)->format('Y-m-d\TH:i:s'),
                    'end'   => $gabunganTamat->format('Y-m-d\TH:i:s'),
                    
                    'color' => $isPast ? '#888888' : '#cc0000',
                    'className' => $isPast ? 'fc-event-past' : '',
                    
                    'extendedProps' => [
                        'courseName'  => $enrollment->course->course_type ?? 'Training Class',
                        'instructor'  => $enrollment->course->instructor->name ?? 'TBA',
                        'location'    => $sesi->gelanggang->gel_name ?? 'TBA',
                        'timeDisplay' => \Carbon\Carbon::parse($sesi->start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($sesi->end_time)->format('h:i A')
                    ]
                ];
            }
        }

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