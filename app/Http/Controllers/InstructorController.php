<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\Cawangan; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class InstructorController extends Controller
{
    public function index()
    {
        // Biasanya index ni untuk papar senarai, tapi memandangkan bos 
        // gabungkan dalam Users Directory, kita biarkan kosong.
    }

    public function create()
    {
        // Tarik semua senarai cawangan dari database
        $cawangans = Cawangan::all(); 

        // Hantar variable $cawangans ke fail HTML (blade)
        return view('instructor.create', compact('cawangans'));
    }

    public function store(Request $request)
    {
        // 1. Validate the incoming form data (Tambah caw_ID)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:instructor',
            'password' => 'required|string|min:8',
            'tel_number' => 'required|string|max:20',
            'address' => 'required|string',
            'caw_ID' => 'required', // Pastikan staf wajib pilih Cawangan
        ]);

        // 2. Create the new instructor (Simpan caw_ID sekali)
        Instructor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encrypts the password
            'tel_number' => $request->tel_number,
            'address' => $request->address,
            'caw_ID' => $request->caw_ID, // Simpan ID Cawangan ke database
        ]);

        // 3. Redirect back to the form with a success message
        return redirect()->route('instructors.create')->with('success', 'Instructor registered successfully!');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        // 1. Cari data instructor berdasarkan instructor_ID
        $instructor = Instructor::where('instructor_ID', $id)->firstOrFail();
        
        // 2. Tarik senarai cawangan untuk letak dalam dropdown
        $cawangans = Cawangan::all();

        // 3. Hantar (redirect) ke muka surat edit beserta data tadi
        return view('instructor.edit', compact('instructor', 'cawangans'));
    }

    public function update(Request $request, $id)
    {
        // 1. Cari instructor yang nak di-edit
        $instructor = Instructor::where('instructor_ID', $id)->firstOrFail();

        // 2. Validate data form
        $request->validate([
            'name' => 'required|string|max:255',
            // Pengecualian email supaya tak clash dengan email dia sendiri
            'email' => 'required|string|email|max:255|unique:instructor,email,' . $instructor->instructor_ID . ',instructor_ID',
            'tel_number' => 'required|string|max:20',
            'address' => 'required|string',
            'caw_ID' => 'required',
            'password' => 'nullable|string|min:8', // Password optional
        ]);

        // 3. Kumpul data yang nak di-update
        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'tel_number' => $request->tel_number,
            'address' => $request->address,
            'caw_ID' => $request->caw_ID,
        ];

        // 4. Semak kalau staf ada masukkan password baru
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        // 5. Update database
        $instructor->update($dataToUpdate);

        // 6. REDIRECT: Hantar balik ke senarai Users
        return redirect()->route('users.index')->with('success', 'Instructor profile updated successfully!');
    }

    public function destroy($id)
    {
        // 1. Cari instructor
        $instructor = Instructor::where('instructor_ID', $id)->firstOrFail();
        
        // 2. Delete dari database
        $instructor->delete();

        // 3. REDIRECT: Hantar balik ke senarai Users lepas delete
        return redirect()->route('users.index')->with('success', 'Instructor deleted successfully!');
    }

    // ==========================================================
    // SEKSYEN BARU: PENGURUSAN KEHADIRAN (ATTENDANCE)
    // ==========================================================

    // KITA EJAS SINI: Fungsi untuk paparkan page Attendance
    public function attendanceIndex()
    {
        $instructor_id = Auth::guard('instructor')->user()->instructor_ID ?? Auth::guard('instructor')->id();
        
        // Tarik kursus yang diajar oleh cikgu ini beserta enrolmen
        $courses = \App\Models\Course::with(['enrollments.user', 'instructor'])
                    ->where('instructor_ID', $instructor_id)
                    ->get();

        return view('instructor.attendance', compact('courses'));
    }

    public function storeAttendance(Request $request)
    {
        // 1. Dapatkan senarai ID pelajar yang di-tick (checkbox)
        $attended_users = $request->input('attendance', []); // Array ID pelajar
        $session_id = $request->input('session_id');
        $date = $request->input('attendance_date', date('Y-m-d')); // Guna tarikh yang dipilih atau harini

        // 2. Jika tiada sesi dipilih, buang error
        if (!$session_id) {
            return back()->with('error', 'Session ID is missing!');
        }

        // 3. Padam rekod kehadiran lama untuk tarikh & sesi ini (supaya tak bertindih kalau cikgu edit/kemaskini)
        // KITA EJAS SINI: Betulkan huruf kecil
        \App\Models\Attendance::where('session_id', $session_id)
            ->where('date', $date)
            ->delete();

        // 4. Masukkan rekod baru yang di-tick oleh cikgu
        foreach ($attended_users as $user_id) {
            \App\Models\Attendance::create([
                'session_id' => $session_id, // KITA EJAS SINI: Betulkan huruf kecil
                'user_id'    => $user_id,    // KITA EJAS SINI: Betulkan huruf kecil
                'date'       => $date,
                'status'     => 'Hadir'
            ]);
        }

        return back()->with('success', 'Attendance for ' . \Carbon\Carbon::parse($date)->format('d M Y') . ' saved successfully!');
    }
}