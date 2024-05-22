<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use App\Models\Motor;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
    
        if (Auth::guard('admin')->attempt($credentials)) {
            Log::info('Login successful for username: ' . $credentials['username']);
            
            $admin = Auth::guard('admin')->user();
            
            $motorId = $admin->motor_id;

            $request->session()->put('motor_id', $motorId);
            
            if ($motorId) {
                return redirect()->route('user.search', ['q' => $motorId]);
            } else {
                return redirect()->intended('user');
            }
        } else {
            Log::warning('Login failed for username: ' . $credentials['username']);
            return back()->withErrors(['message' => 'Invalid username or password.']);
        }
    }

    public function logout(Request $request){
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}