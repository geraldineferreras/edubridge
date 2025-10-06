<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use App\Models\GroupSession;
use Illuminate\Http\Request;
use App\Services\GoogleCalendarService;

class MentorController extends Controller
{
    /**
     * Browse mentors with search and pagination
     */
    public function index(Request $request)
    {
        // Search filter
        $search = $request->input('search');

        $query = Mentor::query();

        if ($search) {
            $query->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('skills', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('industries', 'like', "%{$search}%")
                  ->orWhere('years_experience', 'like', "%{$search}%");
        }

        // Paginate mentors (6 per page)
        $mentors = $query->paginate(6);

        return view('mentee.mentee-d-bm', compact('mentors', 'search'));
    }

    /**
     * Show a single mentor profile
     */
    public function show($id)
    {
        $mentor = Mentor::findOrFail($id);
        return view('mentee.mentee-d-mp', compact('mentor'));
    }

    /**
     * Schedule a session with a mentor + create Google Meet link
     */
    public function schedule(Request $request, GoogleCalendarService $googleCalendar, $mentorId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
        ]);

        $mentor = Mentor::findOrFail($mentorId);

        // 📅 Create Google Calendar event & get Meet link
        $meetLink = $googleCalendar->createEvent(
            $request->title,
            $request->description,
            $request->start_time
        );

        // Save session in DB
        $session = GroupSession::create([
            'mentor_id'   => $mentor->id,
            'title'       => $request->title,
            'description' => $request->description,
            'start_time'  => $request->start_time,
            'meet_link'   => $meetLink,
        ]);

        return redirect()->route('sessions.show', $session)->with('success', 'Session scheduled with Google Meet link!');
    }
}
