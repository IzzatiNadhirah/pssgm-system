<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Staff;
use App\Models\Instructor;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1. Try to login as a regular User
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success', 'Welcome back, Member!');
        }

        // 2. Try checking Staff table manually
        $staff = Staff::where('email', $request->email)->first();
        if ($staff && Hash::check($request->password, $staff->password)) {
            // THIS LINE WAS MISSING: Log the staff member in
            Auth::guard('staff')->login($staff); 
            $request->session()->regenerate();
            return redirect()->intended('/staff/dashboard')->with('success', 'Welcome, Staff!');
        }

        // 3. Try Instructor table
        $instructor = Instructor::where('email', $request->email)->first();
        if ($instructor && Hash::check($request->password, $instructor->password)) {
            // THIS LINE WAS MISSING: Log the instructor in
            Auth::guard('instructor')->login($instructor);
            $request->session()->regenerate();
            return redirect()->intended('/instructor/dashboard')->with('success', 'Welcome, Instructor!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Log out of whichever guard is currently active
        if (Auth::guard('instructor')->check()) {
            Auth::guard('instructor')->logout();
        } elseif (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        } else {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}