<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Required to get the logged-in user
use Illuminate\Support\Str;

class MembershipController extends Controller
{
    public function create()
    {
        // No need to fetch User::all() since the logged-in user is the one paying
        return view('membership.create');
    }

    public function store(Request $request)
    {
        // 1. Validate the form data
        $request->validate([
            'member_type' => 'required|in:Tahunan,Sepanjang Hayat',
            'payment_method' => 'required|string', // e.g., 'ToyyibPay', 'Online Banking'
        ]);

        // 2. Automatically set the exact amount based on the package
        $amount = ($request->member_type === 'Tahunan') ? 20.00 : 200.00;

        // 3. Create the Membership linked to the logged-in user
        $membership = Membership::create([
            'user_ID' => Auth::user()->user_ID,
            'member_type' => $request->member_type,
            'member_code' => 'MEM-' . strtoupper(Str::random(6)),
        ]);

        // 4. Create the Payment linked to the Membership
        Payment::create([
            'payment_code' => 'PAY-' . strtoupper(Str::random(6)),
            'amount' => $amount,
            'payment_date' => now(), 
            'payment_status' => 'Paid', 
            // 'receipt_path' is left blank for now until you build the upload feature
            'member_ID' => $membership->member_ID, 
        ]);

        // 5. Redirect back to dashboard to continue the flowchart sequence
        return redirect()->route('dashboard')->with('success', 'Membership registered and Payment recorded successfully!');
    }
}