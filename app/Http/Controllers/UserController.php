<?php

namespace App\Http\Controllers;

use App\Models\Using;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $request->validate([
            'username' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = new Using();
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'User created successfully.']);
    }
}
