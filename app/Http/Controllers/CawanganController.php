<?php

namespace App\Http\Controllers;

use App\Models\Cawangan;
use App\Models\Staff;
use Illuminate\Http\Request;

class CawanganController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        // Fetch all staff members to populate the dropdown
        $staffs = Staff::all(); 
        
        return view('cawangan.create', compact('staffs'));
    }

    public function store(Request $request)
    {
        // 1. Validate the incoming form data
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'staff_ID' => 'required',
        ]);

        // 2. Map form data to actual database columns
        Cawangan::create([
            'caw_name' => $request->name,
            'caw_address' => $request->address,
            'staff_ID' => $request->staff_ID,
        ]);

        // 3. Redirect back to the form with a success message
        return redirect()->route('cawangans.create')->with('success', 'Cawangan registered successfully!');
    }

    public function show(Cawangan $cawangan)
    {
        //
    }

    public function edit(Cawangan $cawangan)
    {
        //
    }

    public function update(Request $request, Cawangan $cawangan)
    {
        //
    }

    public function destroy(Cawangan $cawangan)
    {
        //
    }
}