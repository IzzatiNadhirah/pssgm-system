<?php

namespace App\Http\Controllers;

use App\Models\Gelanggang;
use App\Models\Cawangan;
use App\Models\Instructor;
use Illuminate\Http\Request;

class GelanggangController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $cawangans = Cawangan::all(); 
        $instructors = Instructor::all(); 
        
        return view('gelanggang.create', compact('cawangans', 'instructors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'caw_ID' => 'required',
            'instructor_ID' => 'required',
        ]);

        Gelanggang::create([
            'gel_name' => $request->name,
            'gel_address' => $request->address,
            'caw_ID' => $request->caw_ID,
            'instructor_ID' => $request->instructor_ID,
        ]);

        return redirect()->route('gelanggangs.create')->with('success', 'Gelanggang registered successfully!');
    }

    public function show(Gelanggang $gelanggang)
    {
        //
    }

    public function edit(Gelanggang $gelanggang)
    {
        //
    }

    public function update(Request $request, Gelanggang $gelanggang)
    {
        //
    }

    public function destroy(Gelanggang $gelanggang)
    {
        //
    }
}