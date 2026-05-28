<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Staff; 
use App\Models\Instructor; // KENA TAMBAH NI supaya sistem kenal table instructor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        // PENGHADANG KESELAMATAN: Pastikan hanya Admin yang boleh masuk
        // Kita letak awal-awal sebelum dia panggil data dari database
        if (Auth::guard('staff')->check() && strtolower(Auth::guard('staff')->user()->role) !== 'admin') {
            return redirect()->route('staff.dashboard')->with('error', 'Akses Dihalang! Hanya Super Admin dibenarkan masuk ke Direktori Pengguna.');
        }

        // 1. Ambil semua data ahli biasa 
        $members = User::with('membership')->orderBy('user_ID', 'asc')->get();
        
        // 2. Ambil semua data staf pengurusan
        $staffs = Staff::orderBy('staff_ID', 'asc')->get();
        
        // 3. Ambil semua data jurulatih
        $instructors = Instructor::orderBy('instructor_ID', 'asc')->get();
        
        // 4. Hantar semua data ke paparan
        return view('user.index', compact('members', 'staffs', 'instructors'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        // 1. Validate the incoming form data
        $request->validate([
            'name' => 'required|string|max:255',
            'icNo' => ['required', 'string', 'regex:/^[0-9]{6}-[0-9]{2}-[0-9]{4}$/', 'unique:users'],
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'tel_number' => 'required|string|max:20',
            'bengkung_level' => 'required|string|max:50',
            'address' => 'required|string',
        ], [
            // Custom error message for the IC format
            'icNo.regex' => 'The IC Number must follow the format: 000000-00-0000 (include the dashes).',
        ]);

        // 2. Create the new user
        User::create([
            'name' => $request->name,
            'icNo' => $request->icNo,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'tel_number' => $request->tel_number,
            'bengkung_level' => $request->bengkung_level,
            'address' => $request->address,
        ]);

        // 3. Redirect back to the index page so HQ can see the new member in the list
        return redirect()->route('users.index')->with('success', 'User registered successfully!');
    }

    public function show($id)
    {
        //
    }

    // UPDATED: Fetch the specific user and load the edit view
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    // UPDATED: Process the edited form data and save to database
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            // Ignore the current user's IC and Email so it doesn't throw a "already taken" error if they don't change it
            'icNo' => ['required', 'string', 'regex:/^[0-9]{6}-[0-9]{2}-[0-9]{4}$/', 'unique:users,icNo,' . $id . ',user_ID'],
            'email' => 'required|string|email|max:255|unique:users,email,' . $id . ',user_ID',
            'tel_number' => 'required|string|max:20',
            'bengkung_level' => 'required|string|max:50',
            'address' => 'required|string',
        ]);

        // Only update the password if the admin typed a new one
        $data = [
            'name' => $request->name,
            'icNo' => $request->icNo,
            'email' => $request->email,
            'tel_number' => $request->tel_number,
            'bengkung_level' => $request->bengkung_level,
            'address' => $request->address,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    // UPDATED: Delete the user from the database
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}