<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mentor;

class MatchmakingController extends Controller
{
    public function index()
    {
        return view('mentee-d-matchmaking');
    }

    public function match(Request $request)
    {
        $mentee = [
            'skills' => explode(',', $request->input('skills')),
            'age' => $request->input('age'),
            'gender' => $request->input('gender'),
            'country' => auth()->user()->country ?? 'Vietnam'
        ];

        $mentors = Mentor::all();
        $bestMatch = null;
        $highestScore = -1;

        foreach ($mentors as $mentor) {
            $mentorSkills = json_decode($mentor->skills, true);
            $score = 0;

            // Skills
            foreach ($mentee['skills'] as $skill) {
                if(in_array(trim($skill), $mentorSkills)) $score += 2;
            }

            // Age
            $ageDiff = abs($mentee['age'] - $mentor->age);
            $score += ($ageDiff < 10) ? 2 : (($ageDiff < 20) ? 1 : 0);

            // Gender
            if($mentee['gender'] == $mentor->gender) $score += 1;

            // Country
            if($mentee['country'] == $mentor->country) $score += 1;

            if($score > $highestScore){
                $highestScore = $score;
                $bestMatch = $mentor;
            }
        }

        return view('mentee-d-matchmaking', compact('bestMatch'));
    }
}
