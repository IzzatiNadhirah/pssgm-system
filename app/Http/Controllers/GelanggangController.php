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
        // 1. Kenal pasti siapa yang tengah login
        $staff = Auth::guard('staff')->user();

        // 2. Kalau SUPER ADMIN: Tarik SEMUA data gelanggang (yang dah approve)
        if ($staff->role === 'admin') { // DITUKAR KE 'admin'
            $activeGelanggangs = Gelanggang::with(['cawangan', 'instructor'])
                                           ->where('status', 'approved')
                                           ->get();
        } 
        // 3. Kalau STAF BIASA (Pengurus Cawangan): Tarik gelanggang cawangan dia je
        else {
            // Cari cawangan mana yang staf ni jaga
            $myCawangan = Cawangan::where('staff_ID', $staff->staff_ID ?? $staff->id)->first();

            // Kalau staf ni memang ada pegang cawangan, tarik gelanggang dia
            if ($myCawangan) {
                $activeGelanggangs = Gelanggang::with(['cawangan', 'instructor'])
                                               ->where('caw_ID', $myCawangan->caw_ID)
                                               ->where('status', 'approved')
                                               ->get();
            } 
            // Kalau staf ni takde pegang apa-apa cawangan lagi
            else {
                $activeGelanggangs = collect(); 
            }
        }

        return view('gelanggang.index', compact('activeGelanggangs'));
    }

    public function create()
    {
        $user = Auth::guard('staff')->user();

        // 1. Check if it's the Super Admin or Regular Staff
        if ($user->role === 'admin') { // DITUKAR KE 'admin'
            $cawangans = Cawangan::all(); // HQ can assign to any branch
        } else {
            // Regular staff can ONLY register for the branch they manage
            $cawangans = Cawangan::where('staff_ID', $user->staff_ID ?? $user->id)->get(); 
        }
        
        $instructors = Instructor::all(); 
        
        return view('gelanggang.create', compact('cawangans', 'instructors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:gelanggang,gel_name',
            'address' => 'required|string',
            'caw_ID' => 'required',
            'instructor_ID' => 'required',
        ], [
            'name.unique' => 'Maaf, nama gelanggang ini sudah didaftarkan di dalam sistem. Sila guna nama lain.',
        ]);

        Gelanggang::create([
            'gel_name' => $request->name,
            'gel_address' => $request->address,
            'caw_ID' => $request->caw_ID,
            'instructor_ID' => $request->instructor_ID,
            'status' => 'pending', 
        ]);

        return redirect()->route('gelanggangs.create')
            ->with('success', 'Gelanggang application submitted and is pending HQ approval!');
    }

    public function show(Gelanggang $gelanggang)
    {
        //
    }

    // ==========================================
    // FUNGSI EDIT & UPDATE
    // ==========================================
    public function edit($id)
    {
        $gelanggang = Gelanggang::findOrFail($id);
        $user = Auth::guard('staff')->user();

        // Sama macam create, Super Admin nampak semua cawangan, ...
        if ($user->role === 'admin') { // DITUKAR KE 'admin'
            $cawangans = Cawangan::all();
        } else {
            $cawangans = Cawangan::where('staff_ID', $user->staff_ID ?? $user->id)->get(); 
        }
        
        $instructors = Instructor::all(); 

        return view('gelanggang.edit', compact('gelanggang', 'cawangans', 'instructors'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:gelanggang,gel_name,' . $id . ',gel_ID',
            'address' => 'required|string',
            'caw_ID' => 'required',
            'instructor_ID' => 'required',
        ], [
            'name.unique' => 'Maaf, nama gelanggang ini sudah didaftarkan di dalam sistem. Sila guna nama lain.',
        ]);

        $gelanggang = Gelanggang::findOrFail($id);
        
        $gelanggang->update([
            'gel_name' => $request->name,
            'gel_address' => $request->address,
            'caw_ID' => $request->caw_ID,
            'instructor_ID' => $request->instructor_ID,
        ]);

        return redirect()->route('gelanggangs.index')
            ->with('success', 'Gelanggang details have been updated successfully!');
    }
    // ==========================================

    public function destroy($id)
    {
        $gelanggang = Gelanggang::findOrFail($id);
        $gelanggang->delete();

        return redirect()->route('gelanggangs.index')->with('success', 'Gelanggang deleted successfully.');
    }

    // ==========================================
    // SUPER ADMIN APPROVAL WORKFLOW
    // ==========================================

    // Fetch pending applications for the Super Admin view
    public function pending()
    {
        // DITUKAR KE 'admin'
        if (Auth::guard('staff')->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Eager load the relationships
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