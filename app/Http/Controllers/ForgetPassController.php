<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgetPassController extends Controller
{
    function ForgetPass(){
        return view('forgot-password');
    }
    function ForgetPassPost(Request $request){
        $request->validate([
            'email'=> "required|email|exists:admins",
        ]);

        $email = $request->email;
        $token = Str::random(64);

        DB::table('password_resets')->where('email', $email)->delete();
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        if (is_null($email)) {
            return redirect()->back()->with('error', 'Email is null');
        }

        if (is_null($token)) {
            return redirect()->back()->with('error', 'Token is null');
        }

        try {
        Mail::send("emails.forgot-password", ['token' => $token], function($message) use ($request){
            $message->to($request->email);
            $message->subject("Reset Password");
        });

        return redirect()->to(route("forgot.password"))
        ->with('success', 'We have sent an email to reset password.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    function ResetPass($token){
        return view("new-password", compact('token'));
    }

    function ResetPassPost(Request $request){
        $request->validate([
            "email" => "required|email|exists:admins",
            "password" => "required|string|min:6|confirmed",
            "password_confirmation" => "required"
        ]);

        $updatedPassword = DB::table('password_resets')
            ->where([
                "email" =>$request->email,
                "token" =>$request->token
            ])->first();
        
        if (!$updatedPassword){
            return redirect()->to(route("reset.pass"))->with("error", "invalid");
        }

        Admin::where("email", $request->email)
            ->update(["password" => Hash::make($request->password)]);

        DB::table("password_resets")->where(["email" => $request->email])->delete();

        return redirect()->to(route("login"))->with("success", "Password reset success");
    }
}
