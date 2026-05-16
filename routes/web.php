<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;           // Added for Logout
use Illuminate\Support\Facades\Auth;   // Added for Logout
use App\Http\Controllers\UserController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\CawanganController;
use App\Http\Controllers\GelanggangController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SessionTimetableController;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\EnrollmentController; // <--- 1. TAMBAH INI

// ==========================================
// 1. PUBLIC ROUTES (Anyone can access)
// ==========================================
Route::get('/', function () {
    return view('welcome');
});

// Login Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Registration Routes (Public so new people can sign up)
Route::resource('users', UserController::class)->only(['create', 'store']);
Route::resource('staffs', StaffController::class)->only(['create', 'store']);
Route::resource('instructors', InstructorController::class)->only(['create', 'store']);


// ==========================================
// 2. SECURE ROUTES (Must be logged in)
// ==========================================

// --- SHARED SECURE ROUTES (Accessible by ANY logged-in role) ---
Route::middleware(['auth:web,staff,instructor'])->group(function () {
    
    // Ghost-Busting Logout Route
    Route::post('/logout', function (Request $request) {
        Auth::guard('web')->logout();
        Auth::guard('staff')->logout();
        Auth::guard('instructor')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    })->name('logout');

    // Management Routes
    Route::resource('cawangans', CawanganController::class);
    Route::resource('gelanggangs', GelanggangController::class);
    Route::resource('courses', CourseController::class);
    
    // Custom routes untuk Instructor set jadual
    Route::get('/courses/{id}/schedule', [CourseController::class, 'editSchedule'])->name('courses.schedule');
    Route::put('/courses/{id}/schedule', [CourseController::class, 'updateSchedule'])->name('courses.update_schedule');
    
    Route::resource('memberships', MembershipController::class);
    // Buang ->middleware('auth') kat hujung ni sebab dah duduk dalam group
    Route::get('/membership/history', [MembershipController::class, 'history'])->name('membership.history'); 
    
    Route::resource('payments', PaymentController::class);
    Route::resource('sessions', SessionTimetableController::class);
    // Custom route untuk delete session guna composite key (Course ID & User ID)
    Route::delete('sessions/{course_id}/{user_id}', [SessionTimetableController::class, 'destroy'])->name('sessions.destroy_custom');

    // Protected actions
    Route::resource('users', UserController::class)->except(['create', 'store']);
    Route::resource('staffs', StaffController::class)->except(['create', 'store']);
    Route::resource('instructors', InstructorController::class)->except(['create', 'store']);
});

// --- USER ONLY ROUTES ---
Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', function () { 
        return view('user.dashboard'); 
    })->name('dashboard');

    // <--- ROUTE ENROLLMENT --->
    Route::post('/enroll/{course_id}', [EnrollmentController::class, 'store'])->name('enroll.store');
    
    // <--- ROUTE DROP CLASS (TARIK DIRI) --->
    Route::delete('/enroll/{id}', [EnrollmentController::class, 'destroy'])->name('enroll.destroy');

    // <--- ROUTE TIMETABLE --->
    Route::get('/my-timetable', [EnrollmentController::class, 'myTimetable'])->name('timetable.index');
});

// --- STAFF ONLY ROUTES ---
Route::middleware(['auth:staff'])->group(function () {
    
    // Pending Gelanggang Routes for Super Admin
    Route::get('/gelanggangs-pending', [GelanggangController::class, 'pending'])->name('gelanggangs.pending');
    Route::post('/gelanggangs/{id}/approve', [GelanggangController::class, 'approve'])->name('gelanggangs.approve');
    Route::post('/gelanggangs/{id}/reject', [GelanggangController::class, 'reject'])->name('gelanggangs.reject');

    Route::get('/staff/dashboard', function () { 
        $staff = Auth::guard('staff')->user();

        // 1. If it is the Super Admin, fetch the stats and load the Admin view
        if ($staff->role === 'super_admin') {
            $totalMembers = \App\Models\User::count();
            $totalGelanggang = \App\Models\Gelanggang::where('status', 'approved')->count(); 
            $totalFees = \App\Models\Payment::sum('amount'); 

            return view('staff.admin_dashboard', compact('totalMembers', 'totalGelanggang', 'totalFees'));
        }

        // 2. If it is regular staff, load the standard view
        return view('staff.dashboard'); 
    })->name('staff.dashboard');
});

// --- INSTRUCTOR ONLY ROUTES ---
Route::middleware(['auth:instructor'])->group(function () {
    Route::get('/instructor/dashboard', function () { 
        return view('instructor.dashboard'); 
    })->name('instructor.dashboard');
});