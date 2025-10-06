<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;

class CustomPasswordResetController extends Controller
{
    // Show email input form
    public function showEmailForm()
{
    return view('auth.forgot-password');
}

    // Handle email and send 4-digit code
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $code = random_int(1000, 9999);
        $email = $request->email;

        // Store code in database (or a custom table if needed)
        DB::table('password_reset_codes')->updateOrInsert(
            ['email' => $email],
            ['code' => $code, 'created_at' => now()]
        );

        // Send email
        Mail::raw("Your 4-digit reset code is: $code", function ($message) use ($email) {
            $message->to($email)
                    ->subject('Password Reset Code');
        });

        session(['reset_email' => $email]);

        return redirect()->route('password.code.form')->with('status', 'Code sent to your email!');
    }

    // Show form for entering code + new password
    public function showCodeForm()
    {
        return view('auth.forgot-password2');
    }

    // Verify code and reset password
    public function verifyAndReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|digits:4',
            'password' => 'required|confirmed|min:6',
        ]);

        $record = DB::table('password_reset_codes')
                    ->where('email', $request->email)
                    ->where('code', $request->code)
                    ->first();

        if (!$record) {
            return back()->withErrors(['code' => 'Invalid or expired code.']);
        }

        // Update password
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Remove code after use
        DB::table('password_reset_codes')->where('email', $request->email)->delete();

        return redirect('/login')->with('status', 'Password successfully reset. You can now log in.');
    }
}
