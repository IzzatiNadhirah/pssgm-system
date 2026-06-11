<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use App\Models\Cawangan;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation (Semak input)
        $request->validate([
            'member_type' => 'required|string',
            'payment_method' => 'required|string',
            // Wajibkan upload resit HANYA jika Manual Transfer dipilih (Max 2MB, Format PDF/Image)
            'receipt_file' => 'required_if:payment_method,Manual Transfer|mimes:pdf,jpg,jpeg,png|max:2048' 
        ], [
            // Custom error message supaya mesra pengguna
            'receipt_file.required_if' => 'Please upload your payment receipt for Manual Bank Transfer.',
            'receipt_file.mimes' => 'Receipt must be in PDF, JPG, or PNG format.',
            'receipt_file.max' => 'Receipt file size must not exceed 2MB.'
        ]);

        // 2. Tentukan amaun berdasarkan pakej keahlian
        $amount = ($request->member_type == 'Tahunan') ? 20.00 : 200.00;

        // 3. Proses Upload Fail Resit (Jika ada fail dihantar)
        $receiptPath = null;
        if ($request->hasFile('receipt_file')) {
            // Fail akan disimpan dalam folder: storage/app/public/receipts
            $receiptPath = $request->file('receipt_file')->store('receipts', 'public');
        }

        // 4. Tetapkan Status Bayaran
        // Kalau Manual Transfer, kita set 'Pending Verification' untuk staf semak.
        $status = ($request->payment_method == 'Manual Transfer') ? 'Pending Verification' : 'Pending';

        // 5. Simpan rekod dalam jadual 'payment'
        Payment::create([
            // Jana kod rujukan bayaran rawak (Contoh: PAY-A1B2C3D4)
            'payment_code' => 'PAY-' . strtoupper(substr(uniqid(), -8)), 
            'amount' => $amount,
            'payment_date' => now(),
            'payment_status' => $status,
            'receipt_path' => $receiptPath, 
            // Pastikan kita guna user_ID atau id mengikut struktur table users bos
            'member_ID' => Auth::user()->user_ID ?? Auth::id(), 
        ]);

        // 6. Redirect ke dashboard
        return redirect()->route('dashboard')->with('success', 'Registration submitted successfully! Please wait for the admin to verify your payment.');
    }

    // ==========================================================
    // SEKSYEN STAFF CAWANGAN / ADMIN UNTUK KELULUSAN
    // ==========================================================

    // Paparkan senarai resit untuk disemak oleh admin
    public function staffIndex()
    {
        $staff = Auth::guard('staff')->user();
        
        // Tarik data Payment berserta maklumat User yang memohon
        $query = Payment::with('user');

        // Jika dia bukan Super Admin, tapis ikut cawangan (jika perlu)
        if (strtolower($staff->role) !== 'admin') {
            $staffId = $staff->staff_ID ?? $staff->id;
            $myCawangan = Cawangan::where('staff_ID', $staffId)->first();
            
            // Jika staf takde cawangan, dia tak boleh tengok apa-apa data
            if (!$myCawangan) {
                $query->whereRaw('1 = 0');
            }
        }

        // Paparkan bayaran yang paling baru dahulu
        $payments = $query->orderBy('created_at', 'desc')->get();

        return view('staff.payments.index', compact('payments'));
    }

    // Tindakan Meluluskan Bayaran
    public function approve($id)
    {
        // Guna ID atau custom payment_ID mengikut model bos
        $payment = Payment::where('payment_ID', $id)->orWhere('id', $id)->firstOrFail();
        $payment->update(['payment_status' => 'Approved']);

        // Boleh tambah logik aktifkan keahlian user kat sini jika perlu.
        // Cth: User::where('user_ID', $payment->member_ID)->update(['status' => 'Active']);
        
        return back()->with('success', 'Payment Approved successfully. The receipt is verified.');
    }

    // Tindakan Menolak Bayaran (Cth: Resit palsu/kabur)
    public function reject($id)
    {
        $payment = Payment::where('payment_ID', $id)->orWhere('id', $id)->firstOrFail();
        $payment->update(['payment_status' => 'Rejected']);

        return back()->with('success', 'Payment Rejected. The student needs to upload a new receipt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}