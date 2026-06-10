<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromotionRequest;
use App\Models\User;
use App\Models\Cawangan; 
use App\Models\Enrollment; // Wajib tambah ni untuk panggil data pendaftaran
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    // ==========================================================
    // 1. SEKSYEN INSTRUCTOR (Guru)
    // ==========================================================
    
    // Paparkan senarai permohonan yang cikgu ni dah buat
    public function index()
    {
        $instructorId = Auth::guard('instructor')->user()->instructor_ID ?? Auth::guard('instructor')->id();

        // Tarik semua history permohonan yang cikgu ni pernah hantar
        $requests = PromotionRequest::with('user')
                    ->where('instructor_ID', $instructorId)
                    ->orderBy('created_at', 'desc')
                    ->get();

        // KITA EJAS SINI: Filter pelajar yang PERNAH enroll dengan cikgu ni sahaja
        // Langkah 1: Cari ID pelajar yang berdaftar dengan kelas cikgu ni
        $enrolledStudentIds = Enrollment::whereHas('course', function($query) use ($instructorId) {
                                $query->where('instructor_ID', $instructorId);
                            })->pluck('user_ID')->unique();

        // Langkah 2: Tarik nama pelajar berdasarkan ID yang kita dah tapis tadi
        $students = User::whereIn('user_ID', $enrolledStudentIds)
                    ->orderBy('name', 'asc')
                    ->get();

        return view('instructor.promotions.index', compact('requests', 'students'));
    }

    // Proses simpan borang permohonan baru
    public function store(Request $request)
    {
        $request->validate([
            'user_ID' => 'required',
            'requested_bengkung' => 'required|string',
            'remarks' => 'nullable|string'
        ]);

        $instructorId = Auth::guard('instructor')->user()->instructor_ID ?? Auth::guard('instructor')->id();
        
        $pelajar = User::where('user_ID', $request->user_ID)->firstOrFail();

        PromotionRequest::create([
            'user_ID' => $request->user_ID,
            'instructor_ID' => $instructorId,
            'current_bengkung' => $pelajar->bengkung_level ?? 'Tiada Rekod',
            'requested_bengkung' => $request->requested_bengkung,
            'remarks' => $request->remarks,
            'status' => 'Pending' 
        ]);

        return back()->with('success', 'Cadangan bengkung berjaya dihantar ke Staf Cawangan!');
    }

    // ==========================================================
    // 2. SEKSYEN STAFF CAWANGAN / ADMIN
    // ==========================================================
    
    // Paparkan semua permohonan bengkung untuk tindakan Staf
    public function staffIndex()
    {
        $staff = Auth::guard('staff')->user();

        $query = PromotionRequest::with(['user', 'instructor']);

        // KESELAMATAN: Jika dia bukan Super Admin, tapis permohonan cawangan dia je
        if (strtolower($staff->role) !== 'admin') {
            $staffId = $staff->staff_ID ?? $staff->id;
            $myCawangan = Cawangan::where('staff_ID', $staffId)->first();
            
            if ($myCawangan) {
                // Tarik permohonan dari instructor dalam cawangan staf tersebut
                $query->whereHas('instructor', function($q) use ($myCawangan) {
                    $q->where('caw_ID', $myCawangan->caw_ID);
                });
            } else {
                // Kalau staf takde cawangan, jangan tunjuk apa-apa data
                $query->whereRaw('1 = 0');
            }
        }

        $requests = $query->orderBy('created_at', 'desc')->get();

        return view('staff.promotions.index', compact('requests'));
    }

    // Tindakan MELULUSKAN permohonan
    public function approve($id)
    {
        $promotion = PromotionRequest::findOrFail($id);
        
        // 1. Ubah status permohonan jadi Approved
        $promotion->update(['status' => 'Approved']);

        // 2. Automatik update bengkung baru budak tu dalam table users
        $pelajar = User::where('user_ID', $promotion->user_ID)->first();
        if ($pelajar) {
            $pelajar->update([
                'bengkung_level' => $promotion->requested_bengkung
            ]);
        }

        return back()->with('success', 'Permohonan diluluskan! Tahap bengkung pelajar telah dikemaskini.');
    }

    // Tindakan MENOLAK permohonan
    public function reject($id)
    {
        $promotion = PromotionRequest::findOrFail($id);
        
        // Ubah status permohonan jadi Rejected
        $promotion->update(['status' => 'Rejected']);

        return back()->with('success', 'Permohonan bengkung telah ditolak.');
    }
}