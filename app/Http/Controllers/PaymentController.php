<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use App\Models\Membership; // Wajib untuk cipta rekod ahli
use App\Models\Cawangan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Wajib untuk jana kod rawak
use Carbon\Carbon; // Wajib untuk kira tarikh luput

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        // 1. Semak input
        $request->validate([
            'member_type' => 'required|string',
            'payment_method' => 'required|string',
            'receipt_file' => 'required_if:payment_method,Manual Transfer|mimes:pdf,jpg,jpeg,png|max:2048' 
        ], [
            'receipt_file.required_if' => 'Please upload your payment receipt for Manual Bank Transfer.',
            'receipt_file.mimes' => 'Receipt must be in PDF, JPG, or PNG format.',
            'receipt_file.max' => 'Receipt file size must not exceed 2MB.'
        ]);

        // 2. Tentukan harga dan tarikh luput
        $amount = ($request->member_type == 'Tahunan') ? 20.00 : 200.00;
        $expiryDate = ($request->member_type == 'Tahunan') ? now()->addYear() : null;

        // 3. Proses Upload Resit
        $receiptPath = null;
        if ($request->hasFile('receipt_file')) {
            $receiptPath = $request->file('receipt_file')->store('receipts', 'public');
        }

        // 4. Tetapkan status
        // Kalau budak tu bayar cash kat cikgu, kita terus letak 'Paid'. Kalau transfer, kena tunggu 'Pending Verification'.
        $status = ($request->payment_method == 'Manual Transfer') ? 'Pending Verification' : 'Paid';

        $userId = Auth::user()->user_ID ?? Auth::id();

        // ==========================================================
        // 5. GABUNGAN LOGIK: Buat rekod Membership DULU
        // ==========================================================
        $membership = Membership::where('user_ID', $userId)->first();

        if (!$membership) {
            // Kalau belum ada rekod ahli, kita cipta baru!
            $membership = Membership::create([
                'member_code' => 'MEM-' . strtoupper(Str::random(6)),
                'member_type' => $request->member_type,
                'user_ID' => $userId,
                'expired_at' => $expiryDate, 
            ]);
        } else {
            // Kalau dah ada, kita kemas kini je jenis dan tarikh luput baru
            $membership->update([
                'member_type' => $request->member_type,
                'expired_at' => $expiryDate,
            ]);
        }

        // ==========================================================
        // 6. Buat rekod Payment (Guna member_ID yang wujud!)
        // ==========================================================
        Payment::create([
            'payment_code' => 'PAY-' . strtoupper(substr(uniqid(), -8)), 
            'amount' => $amount,
            'payment_date' => now(),
            'payment_status' => $status,
            'receipt_path' => $receiptPath, 
            'member_ID' => $membership->member_ID, // Pakai ID dari jadual membership
        ]);

        // Mesej dinamik ikut kaedah bayaran
        $msg = ($status == 'Pending Verification') 
            ? 'Registration submitted successfully! Please wait for the admin to verify your receipt.' 
            : 'Membership activated successfully! Expiry date: ' . ($expiryDate ? $expiryDate->format('d M Y') : 'Lifetime');

        return redirect()->route('dashboard')->with('success', $msg);
    }

    // ==========================================================
    // SEKSYEN STAFF CAWANGAN / ADMIN UNTUK KELULUSAN
    // ==========================================================

    public function staffIndex()
    {
        $staff = Auth::guard('staff')->user();
        
        // KITA EJAS SINI: Pastikan ia panggil relationship yang betul
        $query = Payment::with('membership.user');

        if (strtolower($staff->role) !== 'admin') {
            $staffId = $staff->staff_ID ?? $staff->id;
            $myCawangan = Cawangan::where('staff_ID', $staffId)->first();
            
            if (!$myCawangan) {
                $query->whereRaw('1 = 0');
            }
        }

        $payments = $query->orderBy('created_at', 'desc')->get();

        return view('staff.payments.index', compact('payments'));
    }

    public function approve($id)
    {
        $payment = Payment::where('payment_ID', $id)->firstOrFail();
        $payment->update(['payment_status' => 'Approved']);

        return back()->with('success', 'Payment Approved successfully. The receipt is verified.');
    }

    public function reject($id)
    {
        $payment = Payment::where('payment_ID', $id)->firstOrFail();
        $payment->update(['payment_status' => 'Rejected']);

        return back()->with('success', 'Payment Rejected. The student needs to upload a new receipt.');
    }

    public function index() {}
    public function create() {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}