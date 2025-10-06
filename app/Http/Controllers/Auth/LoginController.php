<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginMentee(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            return redirect()->intended('/mentee/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function loginMentor(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('mentor')->attempt($credentials)) {
            return redirect()->intended('/mentor/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
}