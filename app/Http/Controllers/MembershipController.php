<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon; // Wajib ada untuk main dengan tarikh

class MembershipController extends Controller
{
    public function create()
    {
        return view('membership.create');
    }

    public function store(Request $request)
    {
        // 1. Validate the form data
        $request->validate([
            'member_type' => 'required|in:Tahunan,Sepanjang Hayat',
            'payment_method' => 'required|string',
        ]);

        // 2. Tentukan harga dan tarikh luput
        $amount = ($request->member_type === 'Tahunan') ? 20.00 : 200.00;
        
        // Logik Tarikh Luput: Kalau tahunan tambah 1 tahun, kalau lifetime biar null
        $expiryDate = ($request->member_type === 'Tahunan') ? now()->addYear() : null;

        // 3. Create the Membership
        $membership = Membership::create([
            'user_ID' => Auth::user()->user_ID,
            'member_type' => $request->member_type,
            'member_code' => 'MEM-' . strtoupper(Str::random(6)),
            'expired_at' => $expiryDate, // Simpan tarikh luput kat sini
        ]);

        // 4. Create the Payment
        Payment::create([
            'payment_code' => 'PAY-' . strtoupper(Str::random(6)),
            'amount' => $amount,
            'payment_date' => now(), 
            'payment_status' => 'Paid', 
            'member_ID' => $membership->member_ID, 
        ]);

        return redirect()->route('dashboard')->with('success', 'Membership activated! Expiry date: ' . ($expiryDate ? $expiryDate->format('d M Y') : 'Lifetime'));
    }

    public function history()
    {
        $membership = Auth::user()->membership;
        $payments = $membership ? $membership->payments()->latest()->get() : collect();

        return view('membership.history', compact('payments'));
    }
}