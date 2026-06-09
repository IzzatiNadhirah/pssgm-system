<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Paparkan borang edit profil
    public function edit()
    {
        // Ambil data user yang tengah login
        $user = Auth::guard('web')->user(); 
        
        return view('profile.edit', compact('user'));
    }

    // Proses simpan data profil yang baru
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('web')->user();

        // Validasi semua data yang dimasukkan (Tinggal Nama, Email, Tel, dan Alamat je)
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . ($user->user_ID ?? $user->id) . ',user_ID',
            'tel_number'   => 'required|string|max:20',
            'address'      => 'required|string',
        ]);

        // Kemas kini data dalam database
        $user->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'tel_number'   => $request->tel_number,
            'address'      => $request->address,
        ]);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}