<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MenteeScheduleController extends Controller
{
    public function index(Request $request)
    {
        
        // Get logged-in mentee using the 'mentee' guard
        $mentee = Auth::guard('mentee')->user();

        // Get mentor IDs for approved applications only
        $mentorIds = $mentee->applications()
    ->whereIn('status', ['approved', 'accepted']) // add 'accepted'
    ->pluck('mentor_id')
    ->toArray();


        // Get schedules for only these mentors
        $schedules = Schedule::with('mentor')
            ->whereIn('mentor_id', $mentorIds)
            ->get();

        // Calendar logic
        $year = $request->query('year', now()->year);
        $month = $request->query('month', now()->month);

        $firstOfMonth = Carbon::createFromDate($year, $month, 1);
        $lastOfMonth = $firstOfMonth->copy()->endOfMonth();

        // Generate all days of the month
        $days = [];
        for ($date = $firstOfMonth->copy(); $date->lte($lastOfMonth); $date->addDay()) {
            $days[] = $date->copy();
        }

        $startWeekday = $firstOfMonth->dayOfWeek; // 0 = Sunday, 1 = Monday, etc.
        $prev = $firstOfMonth->copy()->subMonth();
        $next = $firstOfMonth->copy()->addMonth();

        return view('mentee.mentee-d-calendar', compact(
            'schedules', 'firstOfMonth', 'days', 'startWeekday', 'prev', 'next'
        ));
    }
}
