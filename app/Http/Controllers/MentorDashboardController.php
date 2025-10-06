<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Mentee;
use App\Models\Session;
use App\Models\Application;

class MentorDashboardController extends Controller
{
    public function index()
    {
        $mentor = Auth::user(); // Logged-in mentor

        // ✅ Count pending mentee applications for this mentor
        $menteeRequests = Application::where('mentor_id', $mentor->id)
                                ->where('status', 'pending')
                                ->count();

        // ✅ Total mentees assigned to this mentor (all accepted mentees)
        $totalMentees = Mentee::where('mentor_id', $mentor->id)->count();

        // ✅ Active mentees (status = 'active')
        $activeMentees = Mentee::where('mentor_id', $mentor->id)
                        ->where('last_activity', '>=', now()->subMinutes(2))
                        ->count();

        // ✅ Ongoing sessions (session date is today or later)
        $ongoingSessions = Session::where('mentor_id', $mentor->id)
                            ->where('session_date', '>=', now())
                            ->count();

        // ✅ Completed sessions (session date is in the past)
        $completedSessions = Session::where('mentor_id', $mentor->id)
                            ->where('session_date', '<', now())
                            ->count();

        return view('mentor.mentor-d', compact(
    'mentor',
    'menteeRequests',
    'totalMentees',
    'activeMentees', // now reflects online mentees
    'ongoingSessions',
    'completedSessions'
));

    }
}
