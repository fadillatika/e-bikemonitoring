<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminController extends Controller
{
    public function updateEmail(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['password' => 'The provided password does not match our records.']);
        }

        $admin->email = $request->email;
        $admin->save();

        $request->session()->put('admin_email', $admin->email);

        return back()->with([
            'status'=> 'Email updated successfully!',
            'new_email' => $admin->email,
        ]);
    }
}