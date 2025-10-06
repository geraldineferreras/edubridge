<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenteeLoginController extends Controller
{
    public function login(Request $request)
    {
        // ✅ Validate input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // ✅ Try login with mentee guard
        if (Auth::guard('mentee')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // 🔥 Redirect to mentee dashboard
            return redirect()->route('mentee.dashboard');
        }

        // ❌ If login fails
        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
        ]);
    }
}
