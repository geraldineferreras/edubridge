<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Mentor;

class MentorRegisterController extends Controller
{
    public function store(Request $request)
    {
        // ✅ Validate all fields including age and gender
        $validator = Validator::make($request->all(), [
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|email|unique:mentors,email',
            'password'          => 'required|min:6',
            'age'               => 'required|integer|min:10|max:100',
            'gender'            => 'required|string|in:Male,Female,Other',
            'job_title'         => 'nullable|string|max:255',
            'company'           => 'nullable|string|max:255',
            'location'          => 'nullable|string|max:255',
            'category'          => 'nullable|string|max:255',
            'skills'            => 'nullable|string|max:500',
            'bio'               => 'nullable|string',
            'website'           => 'nullable|string|max:255',
            'twitter'           => 'nullable|string|max:255',
            'years_experience'  => 'nullable|string|max:255',
            'relevant_skills'   => 'nullable|string|max:255',
            'industries'        => 'nullable|string|max:255',
            'mentoring_experience' => 'nullable|string',
            'notable_projects'  => 'nullable|string|max:255',
            'profile_photo'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'resume'            => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // ✅ Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // ✅ Handle resume upload
        if ($request->hasFile('resume')) {
            $data['resume'] = $request->file('resume')->store('resumes', 'public');
        }

        // ✅ Hash the password
        $data['password'] = Hash::make($data['password']);

        // ✅ Create mentor record
        Mentor::create($data);

        return redirect()->route('mentor.congrats');
    }
}