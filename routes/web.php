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

Route::get('/', function () {
    return view('welcome');
});

// Resource Routes
Route::resource('users', UserController::class);
Route::resource('staff', StaffController::class);
Route::resource('instructors', InstructorController::class);
Route::resource('cawangans', CawanganController::class);
Route::resource('gelanggangs', GelanggangController::class);
Route::resource('courses', CourseController::class);
Route::resource('memberships', MembershipController::class);
Route::resource('payments', PaymentController::class);
Route::resource('sessions', SessionTimetableController::class);