<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentorLoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // ✅ Use 'mentor' guard here
        if (Auth::guard('mentor')->attempt($credentials, $request->filled('remember'))) {
            return redirect()->intended('/mentor/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials for mentor.'
        ])->withInput();
    }
}
