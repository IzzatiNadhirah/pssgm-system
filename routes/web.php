<?php

use Illuminate\Support\Facades\Route;
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
Route::middleware(['auth'])->group(function () {

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboards - Now pointing to Blade views
    Route::get('/dashboard', function () { 
        return view('dashboard'); 
    })->name('dashboard');

    Route::get('/staff/dashboard', function () { 
        return view('staff.dashboard'); 
    })->name('staff.dashboard');

    Route::get('/instructor/dashboard', function () { 
        return view('instructor.dashboard'); 
    })->name('instructor.dashboard');

    // Management Routes (Cawangan, Gelanggang, Memberships, etc.)
    Route::resource('cawangans', CawanganController::class);
    Route::resource('gelanggangs', GelanggangController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('memberships', MembershipController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('sessions', SessionTimetableController::class);

    // Protected actions for Users, Staff, Instructors (Viewing, Editing, Deleting)
    Route::resource('users', UserController::class)->except(['create', 'store']);
    Route::resource('staffs', StaffController::class)->except(['create', 'store']);
    Route::resource('instructors', InstructorController::class)->except(['create', 'store']);

});