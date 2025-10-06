<?php

namespace App\Http\Controllers;

use App\Models\GroupSession;
use Illuminate\Support\Facades\Auth;

class MenteeSessionController extends Controller
{
    public function index()
    {
        $mentee = Auth::guard('mentee')->user();

        // Get only mentors that this mentee is accepted with
        $mentorIds = $mentee->applications()
            ->where('status', 'accepted')
            ->pluck('mentor_id')
            ->toArray();

        // Fetch sessions from those mentors only
        $sessions = GroupSession::with('mentor')
            ->whereIn('mentor_id', $mentorIds)
            ->where('status', 'open') // only open sessions
            ->orderBy('start_time', 'asc')
            ->get();

        return view('mentee.mentee-d-session', compact('sessions'));
    }
}
