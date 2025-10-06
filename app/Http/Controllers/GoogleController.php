<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    // Redirect to Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle callback from Google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Create or update user
            $user = User::updateOrCreate(
                [
                    'email' => $googleUser->getEmail(),
                ],
                [
                    'first_name' => $googleUser->getName(), // you may split first/last name if needed
                    'password' => bcrypt(Str::random(16)), // random fallback password
                ]
            );

            Auth::login($user);

            return redirect('/dashboard'); // change to your post-login route

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Failed to login with Google.');
        }
    }
}
