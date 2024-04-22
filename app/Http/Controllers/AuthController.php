<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function generateToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = now()->addWeeks(1); // Set expire time if needed
        $token->save();

        $this->sendTokenViaEmail($user->email, $tokenResult->plainTextToken);

        return response()->json([
            'access_token' => $tokenResult->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at
        ]);
    }

    protected function sendTokenViaEmail($email, $token)
    {
        Mail::raw("Your API token is: {$token}", function ($message) use ($email) {
            $message->from('no-reply@example.com', 'Example App');
            $message->to($email);
            $message->subject('Your API Token');
        });
    }
}