<?php

namespace App\Http\Controllers;

use App\Models\Gelanggang;
use App\Models\Cawangan;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GelanggangController extends Controller
{
    public function index()
    {
        // Fetch only approved Gelanggangs and load the related letter codes
        $activeGelanggangs = Gelanggang::with(['cawangan', 'instructor'])
                                       ->where('status', 'approved')
                                       ->get();
                                       
        return view('gelanggang.index', compact('activeGelanggangs'));
    }

    public function create()
    {
        $user = Auth::guard('staff')->user();

        // 1. Check if it's the Super Admin or Regular Staff
        if ($user->role === 'super_admin') {
            $cawangans = Cawangan::all(); // HQ can assign to any branch
        } else {
            // Regular staff can ONLY register for the branch they manage
            $cawangans = Cawangan::where('staff_ID', $user->staff_ID)->get(); 
        }
        
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
            'status' => 'pending', // FORCES EVERY NEW ENTRY TO 'PENDING'
        ]);

        return redirect()->route('gelanggangs.create')
            ->with('success', 'Gelanggang application submitted and is pending HQ approval!');
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

    // ==========================================
    // SUPER ADMIN APPROVAL WORKFLOW
    // ==========================================

    // Fetch pending applications for the Super Admin view
    public function pending()
    {
        if (Auth::guard('staff')->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized action.');
        }

        // UPDATED: Eager load the relationships
        $pendingGelanggangs = Gelanggang::with(['cawangan', 'instructor'])
                                        ->where('status', 'pending')
                                        ->get();
                                        
        return view('gelanggang.pending', compact('pendingGelanggangs'));
    }

    // Process approval
    public function approve($id)
    {
        $gelanggang = Gelanggang::findOrFail($id);
        $gelanggang->update(['status' => 'approved']);

        return redirect()->route('gelanggangs.pending')->with('success', 'Gelanggang approved successfully. It is now active.');
    }

    // Process rejection
    public function reject($id)
    {
        $gelanggang = Gelanggang::findOrFail($id);
        $gelanggang->update(['status' => 'rejected']);

        return redirect()->route('gelanggangs.pending')->with('success', 'Gelanggang application rejected.');
    }
}