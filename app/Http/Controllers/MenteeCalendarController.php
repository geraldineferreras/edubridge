<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;            // your sessions model (uses mentee_sessions or sessions table per your model)
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class MenteeCalendarController extends Controller
{
    public function index(Request $request)
    {
        // Use Manila timezone as your app context
        $today = Carbon::now('Asia/Manila');

        // Allow switching months via query (?year=2025&month=8), otherwise use current month
        $year  = (int) $request->input('year', $today->year);
        $month = (int) $request->input('month', $today->month);

        $firstOfMonth = Carbon::createFromDate($year, $month, 1, 'Asia/Manila');
        $lastOfMonth  = $firstOfMonth->copy()->endOfMonth();

        // Build a CarbonPeriod of all days in this month
        $period = CarbonPeriod::create($firstOfMonth, $lastOfMonth);
        $days   = collect($period)->map(fn ($d) => $d->copy());

        // For grid alignment (0=Sun..6=Sat)
        $startWeekday = $firstOfMonth->dayOfWeek;

        // For prev/next month navigation
        $prev = $firstOfMonth->copy()->subMonth();
        $next = $firstOfMonth->copy()->addMonth();

        // Get sessions for logged-in mentee for this month only
        $menteeId = auth()->id();
$sessions = Session::where('mentee_id', $menteeId)
    ->whereBetween('session_date', [$firstOfMonth->toDateString(), $lastOfMonth->toDateString()])
    ->orderBy('session_date')
    ->orderBy('start_time')
    ->get();


        return view('mentee.mentee-d-calendar', compact(
            'days', 'sessions', 'firstOfMonth', 'startWeekday', 'prev', 'next'
        ));
    }
}
