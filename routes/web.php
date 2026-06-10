<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;           
use Illuminate\Support\Facades\Auth;   
use App\Http\Controllers\{
    UserController, StaffController, InstructorController, CawanganController,
    GelanggangController, CourseController, MembershipController, PaymentController,
    SessionTimetableController, AuthController, EnrollmentController
};

// ==========================================
// 1. PUBLIC ROUTES
// ==========================================
Route::redirect('/', '/login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::resource('users', UserController::class)->only(['create', 'store']);
Route::resource('staffs', StaffController::class)->only(['create', 'store']);
Route::resource('instructors', InstructorController::class)->only(['create', 'store']);

// ==========================================
// 2. SECURE ROUTES (Wajib Login)
// ==========================================

Route::middleware(['auth:web,staff,instructor'])->group(function () {
    // Logout global
    Route::post('/logout', function (Request $request) {
        Auth::guard('web')->logout();
        Auth::guard('staff')->logout();
        Auth::guard('instructor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    // Akses membaca (Read-only) HANYA untuk index
    Route::resource('courses', CourseController::class)->only(['index']);
    Route::resource('gelanggangs', GelanggangController::class)->only(['index']);
    Route::resource('sessions', SessionTimetableController::class)->only(['index']);
    
    Route::resource('memberships', MembershipController::class);
    Route::get('/membership/history', [MembershipController::class, 'history'])->name('membership.history'); 
    Route::resource('payments', PaymentController::class);
});

// --- MEMBER ONLY ---
Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', fn() => view('user.dashboard'))->name('dashboard');
    Route::post('/enroll/{course_id}', [EnrollmentController::class, 'store'])->name('enroll.store');
    Route::delete('/enroll/{id}', [EnrollmentController::class, 'destroy'])->name('enroll.destroy');
    Route::get('/my-timetable', [EnrollmentController::class, 'myTimetable'])->name('timetable.index');
    // Route untuk Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// --- STAFF & INSTRUCTOR ONLY (Pengurusan Sesi Bersama) ---
// Dua-dua role ni boleh buat, edit, dan padam jadual sesi kelas
Route::middleware(['auth:staff,instructor'])->group(function () {
    Route::resource('sessions', SessionTimetableController::class)->except(['index', 'show']);
});


// --- STAFF & ADMIN ONLY ---
Route::middleware(['auth:staff'])->group(function () {
    // Dashboard Staff/Admin
    Route::get('/staff/dashboard', function () {
        $staff = Auth::guard('staff')->user();
        if ($staff->role === 'admin') {
            return view('staff.admin_dashboard', [
                'countMembers' => \App\Models\User::count(),
                'countInstructors' => \App\Models\Instructor::count(),
                'countStaffs' => \App\Models\Staff::count(),
                'totalGelanggang' => \App\Models\Gelanggang::where('status', 'approved')->count(),
                'totalFees' => \App\Models\Payment::sum('amount')
            ]);
        }
        $myCawangan = \App\Models\Cawangan::where('staff_ID', $staff->staff_ID ?? $staff->id)->first();
        $myGelanggangs = $myCawangan ? \App\Models\Gelanggang::where('caw_ID', $myCawangan->caw_ID)->get() : collect();
        return view('staff.dashboard', compact('myGelanggangs'));
    })->name('staff.dashboard');

    // Pengurusan Penuh (CUD)
    Route::resource('courses', CourseController::class)->except(['index', 'show']);
    Route::resource('gelanggangs', GelanggangController::class)->except(['index', 'show']);
    
    Route::resource('cawangans', CawanganController::class);
    Route::resource('users', UserController::class)->except(['create', 'store']);
    Route::resource('staffs', StaffController::class)->except(['create', 'store']);
    Route::resource('instructors', InstructorController::class)->except(['create', 'store']);

    Route::get('/gelanggangs-pending', [GelanggangController::class, 'pending'])->name('gelanggangs.pending');
    Route::post('/gelanggangs/{id}/approve', [GelanggangController::class, 'approve'])->name('gelanggangs.approve');
    Route::post('/gelanggangs/{id}/reject', [GelanggangController::class, 'reject'])->name('gelanggangs.reject');

    // KITA EJAS SINI: Route untuk Staff urus Bengkung Pelajar (Approve/Reject)
    Route::get('/staff/promotions', [\App\Http\Controllers\PromotionController::class, 'staffIndex'])->name('staff.promotions.index');
    Route::post('/staff/promotions/{id}/approve', [\App\Http\Controllers\PromotionController::class, 'approve'])->name('staff.promotions.approve');
    Route::post('/staff/promotions/{id}/reject', [\App\Http\Controllers\PromotionController::class, 'reject'])->name('staff.promotions.reject');
});


// --- INSTRUCTOR ONLY ---
Route::middleware(['auth:instructor'])->group(function () {
    Route::get('/instructor/dashboard', fn() => view('instructor.dashboard'))->name('instructor.dashboard');
    Route::get('/instructor/enrolled-students', [CourseController::class, 'enrolledStudents'])->name('instructor.enrolled');

    // Route untuk Request Bengkung
    Route::get('/instructor/promotions', [\App\Http\Controllers\PromotionController::class, 'index'])->name('promotions.index');
    Route::post('/instructor/promotions', [\App\Http\Controllers\PromotionController::class, 'store'])->name('promotions.store');
    
    // Instructor boleh ubah jadual kursus dia sendiri
    Route::get('/courses/{id}/schedule', [CourseController::class, 'editSchedule'])->name('courses.schedule');
    Route::put('/courses/{id}/schedule', [CourseController::class, 'updateSchedule'])->name('courses.update_schedule');
});