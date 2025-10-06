<?php

namespace App\Http\Controllers;

use App\Models\GroupSession;
use App\Models\GroupSessionParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\GoogleCalendarService;

class GroupSessionController extends Controller
{
    // ✅ Mentor: list all their sessions
    public function index()
    {
        $sessions = GroupSession::with('mentor')
            ->withCount('participants')
            ->where('mentor_id', Auth::guard('mentor')->id())
            ->orderBy('start_time', 'asc')
            ->get();

        return view('mentor.sessions.mentor-d-session', compact('sessions'));
    }

    // ✅ Mentor: create a scheduled group session
    public function store(Request $request, GoogleCalendarService $googleCalendar)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
            'capacity'    => 'required|integer|min:1',
        ]);

        $mentor = Auth::guard('mentor')->user();

        // 📅 Create Google Calendar event + Meet link
        $event = $googleCalendar->createEvent(
    $data['title'],
    $data['description'] ?? '',
    $data['start_time'],
    $data['end_time']
);

        // 💾 Save session in DB
        GroupSession::create([
            'mentor_id'   => $mentor->id,
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'start_time'  => Carbon::parse($data['start_time']),
            'end_time'    => Carbon::parse($data['end_time']),
            'capacity'    => $data['capacity'],
            'status'      => 'open',
            'room_id'     => 'room_' . Str::random(12),
            'meet_link'   => $event['meet_link'] ?? null,
        ]);

        return redirect()->route('mentor.sessions.index')
            ->with('success', 'Session created successfully with Google Meet link!');
    }

    // ✅ Mentor: start an instant session
    public function instant(Request $request, GoogleCalendarService $googleCalendar)
{
    $mentor = Auth::guard('mentor')->user();

    $now = now();
    $end = $now->copy()->addHour(); // default 1 hour

    // 📅 Create instant Google event (with empty description)
    $event = $googleCalendar->createEvent(
        $request->title ?? 'Instant Meeting',
        '',
        $now->toRfc3339String(),
        $end->toRfc3339String()
    );

    $session = GroupSession::create([
        'mentor_id'   => $mentor->id,
        'title'       => $request->title ?? 'Instant Meeting',
        'description' => null,
        'start_time'  => $now,
        'end_time'    => $end,
        'capacity'    => $request->capacity ?? 10,
        'status'      => 'open',
        'room_id'     => 'room_'.Str::random(12),
        'meet_link'   => $event['meet_link'] ?? null,
    ]);

    return response()->json([
        'room_id'   => $session->room_id,
        'meet_link' => $session->meet_link,
    ]);
}


    // ✅ Mentee: join a session
    public function join($id)
    {
        $session = GroupSession::withCount('participants')->findOrFail($id);

        if ($session->participants_count >= $session->capacity) {
            return back()->with('error', 'Session is full');
        }

        GroupSessionParticipant::firstOrCreate([
            'session_id' => $session->id,
            'mentee_id'  => Auth::guard('mentee')->id(),
        ]);

        if ($session->participants()->count() >= $session->capacity) {
            $session->update(['status' => 'full']);
        }

        return back()->with('success', 'You joined the session!');
    }

    // ✅ Mentee: see open sessions
    public function menteeSessions()
    {
        $mentee = Auth::guard('mentee')->user();

        $mentorIds = $mentee->applications()
            ->where('status', 'approved')
            ->pluck('mentor_id');

        $sessions = GroupSession::with('mentor')
            ->withCount('participants')
            ->whereIn('mentor_id', $mentorIds)
            ->where('status', 'open')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('mentee.sessions.index', compact('sessions'));
    }

    // ✅ Video room page
    public function video($id)
    {
        $session = GroupSession::findOrFail($id);
        $canJoin = now()->greaterThanOrEqualTo($session->start_time);

        return view('sessions.video', compact('session', 'canJoin'));
    }
}
