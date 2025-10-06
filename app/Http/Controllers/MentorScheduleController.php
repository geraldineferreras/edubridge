<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;

class MentorScheduleController extends Controller
{
    public function index()
    {
        // Use the mentor guard to get the logged-in mentor's ID
        $schedules = Schedule::with('mentor')
                             ->where('mentor_id', Auth::guard('mentor')->id())
                             ->get();

        return view('mentor.mentor-d-calendar', compact('schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'course' => 'required|string|max:255',
        ]);

        Schedule::create([
            'mentor_id' => Auth::guard('mentor')->id(), // ✅ mentor ID
            'date' => $request->date,
            'time' => $request->time,
            'course' => $request->course,
        ]);

        return redirect()->route('mentor.calendar')->with('success', 'Schedule added!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'time' => 'required',
            'course' => 'required',
        ]);

        $schedule = Schedule::where('mentor_id', Auth::guard('mentor')->id())
                            ->findOrFail($id);

        $schedule->update([
            'time' => $request->time,
            'course' => $request->course,
        ]);

        return redirect()->route('mentor.calendar')->with('success', 'Schedule updated successfully.');
    }
}

