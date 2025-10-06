<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mentor;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class MenteeController extends Controller
{
    // ✅ Mentee Dashboard
    public function dashboard(Request $request)
    {
        $mentors = Mentor::query();

        if ($request->filled('search')) {
            $mentors->where('first_name', 'like', "%{$request->search}%")
                    ->orWhere('last_name', 'like', "%{$request->search}%")
                    ->orWhere('skills', 'like', "%{$request->search}%");
        }

        $mentors = $mentors->get();
        $mentee = auth()->user();

        return view('mentee.mentee-d', compact('mentors', 'mentee'));
    }

    // ✅ Browse Mentors Page with reviews and ratings
    public function browseMentors(Request $request)
    {
        $mentors = Mentor::withCount('feedbacks')
                         ->withAvg('feedbacks', 'rating');

        if ($request->filled('search')) {
            $mentors->where('first_name', 'like', "%{$request->search}%")
                    ->orWhere('last_name', 'like', "%{$request->search}%")
                    ->orWhere('skills', 'like', "%{$request->search}%");
        }

        $mentors = $mentors->paginate(8);

        return view('mentee.mentee-d-bm', compact('mentors'));
    }

    // ✅ Mentor Profile Page with application status
    public function showProfile($mentorId)
    {
        $mentor = Mentor::with(['feedbacks'])->findOrFail($mentorId);

        $mentor->skills = explode(',', $mentor->skills ?? '');
        $mentor->feedbacks = $mentor->feedbacks()->latest()->get();
        $mentor->average_rating = $mentor->feedbacks->avg('rating') ?? 0;
        $mentor->total_reviews = $mentor->feedbacks->count();

        $mentee = auth()->user();
        $application = Application::where('mentee_id', $mentee->id)
                                  ->where('mentor_id', $mentor->id)
                                  ->first();

        $mentor->already_applied = $application ? true : false;
        $mentor->application_status = $application->status ?? null;

        return view('mentee.mentee-d-mp', compact('mentor'));
    }

    // ✅ Store Feedback
    public function storeFeedback(Request $request, $mentorId)
    {
        $request->validate([
            'comment' => 'required|string',
            'rating'  => 'nullable|integer|min:1|max:5'
        ]);

        $mentor = Mentor::findOrFail($mentorId);
        $mentor->feedbacks()->create([
            'author'  => auth()->guard('mentee')->user()->name,
            'comment' => $request->comment,
            'rating'  => $request->rating,
        ]);

        return redirect()->back()->with('success', 'Feedback submitted!');
    }

    // ✅ Mentee Applications Page
    public function applications()
    {
        $mentee = auth()->user();

        $pendingCount    = Application::where('mentee_id', $mentee->id)->where('status', 'pending')->count();
        $processingCount = Application::where('mentee_id', $mentee->id)->where('status', 'processing')->count();
        $completedCount  = Application::where('mentee_id', $mentee->id)->where('status', 'completed')->count();

        $applications = Application::with('mentor')
                                   ->where('mentee_id', $mentee->id)
                                   ->get();

        return view('mentee.mentee-d-application', compact(
            'pendingCount', 
            'processingCount', 
            'completedCount', 
            'applications'
        ));
    }

    // ✅ Show matchmaking page (with Next Mentor support)
    public function matchmaking(Request $request)
    {
        $mentee = auth()->user();

        // Rank mentors by score
        $mentors = $this->rankMentors($mentee);

        // Current index (default 0)
        $index = $request->query('index', 0);

        // Pick the mentor at this index
        $bestMentor = $mentors[$index] ?? null;

        return view('mentee.mentee-d-matchmaking', compact('mentee', 'bestMentor', 'index', 'mentors'));
    }

    // ✅ Rank mentors with scoring
    private function rankMentors($mentee)
    {
        $candidates = Mentor::all();
        $scored = [];

        foreach ($candidates as $mentor) {
            $score = 0;

            // Location
            if ($mentor->location && $mentee->location &&
                strtolower($mentor->location) === strtolower($mentee->location)) {
                $score += 50;
            }

            // Gender
            if ($mentor->gender && $mentee->gender &&
                strtolower($mentor->gender) === strtolower($mentee->gender)) {
                $score += 30;
            }

            // Age closeness
            if ($mentor->age && $mentee->age) {
                $diff = abs($mentor->age - $mentee->age);
                if ($diff <= 5) $score += 20;
                elseif ($diff <= 10) $score += 10;
            }

            // Skills overlap
            $menteeSkills = $mentee->skills ? explode(',', strtolower($mentee->skills)) : [];
            $mentorSkills = $mentor->skills ? explode(',', strtolower($mentor->skills)) : [];
            $common = array_intersect(array_map('trim', $menteeSkills), array_map('trim', $mentorSkills));
            if (!empty($menteeSkills)) {
                $score += (count($common) / count($menteeSkills)) * 100;
            }

            $scored[] = ['mentor' => $mentor, 'score' => $score];
        }

        // Sort mentors by score DESC
        usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);

        // Return just the mentors in ranked order
        return array_column($scored, 'mentor');
    }

    // ✅ Find match manually (filters)
    public function findMatch(Request $request)
    {
        $mentee = auth()->user();

        $filters = [
            'skills' => $request->input('skills'),
            'age'    => $request->input('age'),
            'gender' => $request->input('gender'),
        ];

        $bestMentor = $this->computeBestMentor($mentee, $filters);

        return view('mentee.mentee-d-matchmaking', compact('mentee', 'bestMentor'));
    }

    // ✅ Compute best mentor (still used by applyMatch)
    private function computeBestMentor($mentee, array $filters = [])
    {
        $query = Mentor::query();

        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }
        if (!empty($filters['age'])) {
            $query->whereBetween('age', [(int)$filters['age'] - 10, (int)$filters['age'] + 10]);
        }
        if (!empty($filters['skills'])) {
            foreach (preg_split('/\s*,\s*/', $filters['skills']) as $skill) {
                if ($skill !== '') {
                    $query->where('skills', 'like', "%{$skill}%");
                }
            }
        }

        $candidates = $query->get();
        if ($candidates->isEmpty()) {
            $candidates = Mentor::all();
        }

        $bestMentor = null;
        $bestScore  = -1;

        $mLoc    = trim((string)($mentee->location ?? ''));
        $mGender = trim((string)($mentee->gender ?? ''));
        $mAge    = $mentee->age;
        $mSkills = $mentee->skills ? array_map('mb_strtolower', array_map('trim', explode(',', $mentee->skills))) : [];

        foreach ($candidates as $mentor) {
            $score = 0;

            // Location
            $loc = trim((string)($mentor->location ?? ''));
            if ($loc !== '' && $mLoc !== '' && mb_strtolower($loc) === mb_strtolower($mLoc)) $score += 50;

            // Gender
            $g = trim((string)($mentor->gender ?? ''));
            if ($g !== '' && $mGender !== '' && mb_strtolower($g) === mb_strtolower($mGender)) $score += 30;

            // Age closeness
            $age = $mentor->age;
            if (!is_null($age) && !is_null($mAge)) {
                $diff = abs((int)$age - (int)$mAge);
                if ($diff <= 5)      $score += 20;
                elseif ($diff <=10)  $score += 10;
            }

            // Skills overlap
            $mentorSkills = $mentor->skills
                ? array_map('mb_strtolower', array_map('trim', explode(',', $mentor->skills)))
                : [];

            if (!empty($mSkills)) {
                $common = array_intersect($mSkills, $mentorSkills);
                $score += count($common) / count($mSkills) * 100;
            }

            if ($score > $bestScore) {
                $bestScore  = $score;
                $bestMentor = $mentor;
            }
        }

        return $bestMentor;
    }

    // ✅ Update Mentee Profile
    public function updateProfile(Request $request)
    {
        $mentee = auth()->user();

        $validated = $request->validate([
            'age' => 'nullable|integer|min:10|max:100',
            'location' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $mentee->age = $validated['age'] ?? $mentee->age;
        $mentee->location = $validated['location'] ?? $mentee->location;

        if ($request->hasFile('profile_picture')) {
            $filename = time().'_'.$request->file('profile_picture')->getClientOriginalName();
            $request->file('profile_picture')->move(public_path('uploads/profiles'), $filename);
            $mentee->profile_picture = $filename;
        }

        $mentee->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    // ✅ Apply to the mentor currently displayed (respects index)
    public function applyMatch(Request $request)
    {
        $mentee = auth()->user();
        $index = (int) $request->input('index', 0);

        // Get the ranked mentors
        $mentors = $this->rankMentors($mentee);
        $bestMentor = $mentors[$index] ?? null;

        if (!$bestMentor) {
            return redirect()
                ->route('mentee.matchmaking')
                ->with('error', 'No suitable mentor found.');
        }

        $existing = Application::where('mentee_id', $mentee->id)
                                ->where('mentor_id', $bestMentor->id)
                                ->first();

        if ($existing) {
            return redirect()
                ->route('mentee.matchmaking', ['index' => $index])
                ->with('info', 'You have already applied to this mentor.');
        }

        Application::create([
            'mentee_id' => $mentee->id,
            'mentor_id' => $bestMentor->id,
            'status'    => 'pending',
        ]);

        return redirect()
            ->route('mentee.matchmaking', ['index' => $index])
            ->with('matched', $bestMentor->id);
    }
}
