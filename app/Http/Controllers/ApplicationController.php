<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    // Store a new application (already exists)
    public function store(Request $request)
    {
        Application::create([
            'mentee_id' => Auth::id(),
            'mentor_id' => $request->mentor_id,
            'status' => 'pending'
        ]);

        return response()->json(['success' => true]);
    }

    // Show pending applications for the logged-in mentor
    public function index()
    {
        $mentor = Auth::user();

        $pendingApplications = Application::where('mentor_id', $mentor->id)
                                ->where('status', 'pending')
                                ->get();

        return view('mentor.mentor-d-application', compact('pendingApplications'));
    }

    public function updateStatus($id, $status)
{
    $application = Application::findOrFail($id);
    $application->status = $status;
    $application->save();

    // If mentor accepted the mentee request
    if ($status === 'accepted') {
        $mentee = $application->mentee; // get the mentee
        $mentee->mentor_id = $application->mentor_id;
        $mentee->status = 'active';
        $mentee->save();
    }

    return redirect()->back()->with('success', 'Application status updated!');
}


}
