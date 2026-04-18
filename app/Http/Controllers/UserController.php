<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        //
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

        // 3. Redirect back to the form with a success message
        return redirect()->route('users.create')->with('success', 'User registered successfully!');
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        //
    }
}