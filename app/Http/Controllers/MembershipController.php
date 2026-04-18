<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MembershipController extends Controller
{
    public function create()
    {
        $users = User::all(); 
        return view('membership.create', compact('users'));
    }

    public function store(Request $request)
    {
        // 1. Validate both Membership and Payment data
        $request->validate([
            'user_ID' => 'required',
            'member_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
        ]);

        // 2. Create the Membership first
        // Note: member_code is generated automatically here
        $membership = Membership::create([
            'user_ID' => $request->user_ID,
            'member_type' => $request->member_type,
            'member_code' => 'MEM-' . strtoupper(Str::random(6)),
        ]);

        // 3. Create the Payment linked to the User and the new Membership
        Payment::create([
            'payment_code' => 'PAY-' . strtoupper(Str::random(6)),
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'status' => 'Paid',
            'user_ID' => $request->user_ID,
            'member_ID' => $membership->member_ID, // Link to the membership we just made
        ]);

        return redirect()->route('memberships.create')->with('success', 'Membership registered and Payment recorded successfully!');
    }
}