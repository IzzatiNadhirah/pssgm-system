<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InstructorController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        // Loads your newly created blade file
        return view('instructor.create');
    }

    public function store(Request $request)
    {
        // 1. Validate the incoming form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:instructor',
            'password' => 'required|string|min:8',
            'tel_number' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        // 2. Create the new instructor (ID INS0001 is handled by PostgreSQL)
        Instructor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encrypts the password
            'tel_number' => $request->tel_number,
            'address' => $request->address,
        ]);

        // 3. Redirect back to the form with a success message
        return redirect()->route('instructors.create')->with('success', 'Instructor registered successfully!');
    }

    public function show(Instructor $instructor)
    {
        //
    }

    public function edit(Instructor $instructor)
    {
        //
    }

    public function update(Request $request, Instructor $instructor)
    {
        //
    }

    public function destroy(Instructor $instructor)
    {
        //
    }
}