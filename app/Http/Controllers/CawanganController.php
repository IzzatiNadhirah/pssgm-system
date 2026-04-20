<?php

namespace App\Http\Controllers;

use App\Models\Cawangan;
use App\Models\Staff;
use Illuminate\Http\Request;

class CawanganController extends Controller
{
    public function index()
    {
        // Fetch all branches
        $cawangans = Cawangan::all();
        return view('cawangan.index', compact('cawangans'));
    }

    public function create()
    {
        // Fetch all staff members to populate the dropdown for the Super Admin
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

        // 3. Redirect to the index page with a success message
        return redirect()->route('cawangans.index')->with('success', 'Cawangan registered successfully!');
    }

    public function show(Cawangan $cawangan)
    {
        //
    }

    // UPDATED: Fetch the specific record and load the edit view
    public function edit($id)
    {
        $cawangan = Cawangan::findOrFail($id);
        $staffs = Staff::all(); 
        
        return view('cawangan.edit', compact('cawangan', 'staffs'));
    }

    // UPDATED: Process the edited form data and save to database
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'staff_ID' => 'required',
        ]);

        $cawangan = Cawangan::findOrFail($id);
        
        $cawangan->update([
            'caw_name' => $request->name,
            'caw_address' => $request->address,
            'staff_ID' => $request->staff_ID,
        ]);

        return redirect()->route('cawangans.index')->with('success', 'Cawangan updated successfully!');
    }

    public function destroy($id)
    {
        // Find the specific branch by its custom primary key and delete it
        $cawangan = Cawangan::findOrFail($id);
        $cawangan->delete();

        return redirect()->route('cawangans.index')->with('success', 'Cawangan deleted successfully!');
    }
}