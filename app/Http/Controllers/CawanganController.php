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
        // 1. Cari ID staff yang DAH ADA cawangan (tak termasuk yang null)
        $assignedStaffIds = Cawangan::whereNotNull('staff_ID')
                                    ->pluck('staff_ID')
                                    ->toArray();

        // 2. Tarik senarai staff yang 'free' SAHAJA dan pastikan BUKAN super_admin
        $staffs = Staff::whereNotIn('staff_ID', $assignedStaffIds)
                       ->where('role', '!=', 'super_admin')
                       ->get(); 
        
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

    // UPDATED: Fetch the specific record and load the edit view with FILTERED staff
    public function edit($id)
    {
        $cawangan = Cawangan::findOrFail($id);
        
        // 1. Cari ID staff yang dah pegang cawangan LAIN (Kecuali cawangan yang kita tengah edit ni)
        $assignedStaffIds = Cawangan::whereNotNull('staff_ID')
                                    ->where('caw_ID', '!=', $id)
                                    ->pluck('staff_ID')
                                    ->toArray();

        // 2. Tarik senarai staff yang 'free' + staff yang memang tengah jaga cawangan ni, BUKAN super_admin
        $staffs = Staff::whereNotIn('staff_ID', $assignedStaffIds)
                       ->where('role', '!=', 'super_admin')
                       ->get(); 
        
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