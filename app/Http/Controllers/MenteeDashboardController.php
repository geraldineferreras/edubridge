<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Mentor;

class MenteeDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get the currently logged-in mentee
        $mentee = Auth::guard('mentee')->user();

        // Get search query from input
        $search = $request->input('search');

        // Fetch mentors with optional search
        $mentors = Mentor::when($search, function ($query, $search) {
            return $query->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('job_title', 'like', "%{$search}%")
                         ->orWhere('category', 'like', "%{$search}%")
                         ->orWhere('skills', 'like', "%{$search}%");
        })
        ->take(12)
        ->get();

        // Send data to blade
        return view('mentee.mentee-d', compact('mentee', 'mentors'));
    }
}
