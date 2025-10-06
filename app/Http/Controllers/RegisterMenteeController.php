<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class RegisterMenteeController extends Controller
{
    public function store(Request $request)
    {
        // 1️⃣ Validation
        $validator = Validator::make($request->all(), [
            'first_name'      => 'required|string|max:255',
            'middle_name'     => 'nullable|string|max:255',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|min:6',
            'age'             => 'required|integer|min:10|max:100',
            'location'        => 'required|string|max:255',
            'skills'          => 'nullable|string|max:500',
            'gender'          => 'nullable|string|in:Male,Female,Other',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // new validation
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        // 2️⃣ Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/profiles'), $filename);
        } else {
            $filename = null;
        }

        // 3️⃣ Create user
        User::create([
    'first_name' => $validated['first_name'],
    'middle_name' => $validated['middle_name'] ?? null,
    'last_name' => $validated['last_name'],
    'name' => trim($validated['first_name'].' '.($validated['middle_name'] ?? '').' '.$validated['last_name']),
    'email' => $validated['email'],
    'password' => bcrypt($validated['password']),
    'age' => $validated['age'],
    'location' => $validated['location'],
    'skills' => $validated['skills'] ?? null,
    'gender' => $validated['gender'] ?? null,
    'profile_picture' => $filename ?? null,
]);

        return redirect('/login')->with('success', 'Registration successful!');
    }
}