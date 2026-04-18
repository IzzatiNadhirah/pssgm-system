<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        // 1. Validate the incoming form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:staff',
            'password' => 'required|string|min:8',
        ]);

        // 2. Create the new staff member (ID is handled by your PostgreSQL trigger)
        Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encrypts the password
        ]);

        // 3. Redirect back to the form with a success message
        return redirect()->route('staff.create')->with('success', 'Staff registered successfully!');
    }

    public function show(Staff $staff)
    {
        //
    }

    public function edit(Staff $staff)
    {
        //
    }

    public function update(Request $request, Staff $staff)
    {
        //
    }

    public function destroy(Staff $staff)
    {
        //
    }
}