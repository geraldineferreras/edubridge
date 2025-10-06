<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EduBridge Matchmaking</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/mentee-d-matchmaking.css') }}">
</head>
<body>
<div class="dashboard">
  <aside class="sidebar">
    <div class="logo">
      <img src="{{ asset('img/logo.png') }}" alt="Logo">
    </div>
    <nav>
      <a href="{{ route('mentee.dashboard') }}"><span class="material-icons">home</span>Home</a>
      <a href="{{ route('mentee.applications') }}"><span class="material-icons">assignment</span>Application</a>
      <a href="{{ route('mentee.session') }}"><span class="material-icons">computer</span>Session</a>
      <a href="{{ route('mentee.calendar') }}"><span class="material-icons">calendar_today</span>Calendar</a>
      <a href="{{ route('mentee.messages') }}"><span class="material-icons">chat</span>Messages</a>
      <a class="active" href="{{ route('mentee.matchmaking') }}"><span class="material-icons">diversity_3</span>Match mentors</a>
    </nav>
  </aside>

  <main class="content">
    <header class="top-bar">
      <div class="breadcrumbs">Matchmaking ></div>
      <div class="top-icons">
        <span class="material-icons">notifications</span>
        <span class="material-icons">account_circle</span>
      </div>
    </header>

    <h2 class="page-title">Matchmaking</h2>
    <h1 class="page">Best Mentor for you</h1>

<section class="match-section">

  <!-- Mentee Card -->
  <div class="card mentee">
    <h2>Mentee</h2>
    <img src="{{ $mentee->profile_picture 
                ? asset('uploads/profiles/' . $mentee->profile_picture) 
                : asset('images/default-avatar.png') }}" 
         alt="{{ $mentee->first_name }}'s Profile Picture" 
         class="mentee-avatar">
    <h3>{{ $mentee->first_name }} {{ $mentee->last_name }}</h3>
    <p>Age: {{ $mentee->age ?? 'N/A' }}</p>
    <p>Gender: {{ $mentee->gender ?? 'N/A' }}</p>
    <p>Location: {{ $mentee->location ?? 'N/A' }}</p>
  </div>

  <div class="match-icon">
    <span class="material-icons">handshake</span>
  </div>

  <!-- Mentor Card -->
@isset($bestMentor)
<a href="{{ route('mentee.mentor.show', $bestMentor->id) }}" class="no-underline">
    <div class="card mentor">
        <h2>Mentor</h2>
        <img src="{{ $bestMentor->profile_photo ? asset('storage/'.$bestMentor->profile_photo) : asset('img/default-mentor.png') }}" alt="Mentor Photo">
        <h3>{{ $bestMentor->first_name }} {{ $bestMentor->last_name }}</h3>
        <p>Age: {{ $bestMentor->age ?? 'N/A' }}</p>
        <p>Gender: {{ $bestMentor->gender ?? 'N/A' }}</p>
        <p>Location: {{ $bestMentor->location ?? 'N/A' }}</p>
        <p>Skills: {{ $bestMentor->skills ?? '—' }}</p>

        {{-- Ratings --}}
        @php
            $averageRating = $bestMentor->feedbacks->avg('rating') ?? 0;
            $totalReviews  = $bestMentor->feedbacks->count();
        @endphp
        <p>Rating: 
            @for ($i = 1; $i <= 5; $i++)
                @if($i <= floor($averageRating))
                    <span style="color: gold;">★</span>
                @elseif($i - $averageRating < 1)
                    <span style="color: gold;">★</span>
                @else
                    <span style="color: lightgray;">★</span>
                @endif
            @endfor
            ({{ number_format($averageRating, 1) }}) {{ $totalReviews }} review{{ $totalReviews > 1 ? 's' : '' }}
        </p>
    </div>
</a>
@else
<p>No mentor found yet.</p>
@endisset
</section>

<!-- Match Button -->
<form method="POST" action="{{ route('mentee.matchmaking.apply') }}">
    @csrf
    <input type="hidden" name="index" value="{{ $index }}">
    <div class="btn-wrapper">
        <button type="submit" class="btn">Let's Match</button>
    </div>
</form>

@if(isset($bestMentor))
    <div class="btn-wrapper1">
        {{-- Next Mentor button as Material Icon --}}
        @if($index + 1 < count($mentors))
            <a href="{{ route('mentee.matchmaking', ['index' => $index + 1]) }}" class="btn">
                <span class="material-icons" style="font-size:2.5rem;">east</span>
            </a>
        @endif
    </div>
@endif


   <!-- Matching Popup -->
<!-- Match Success / Info Popup -->
@if(session('matched'))
    @php
        $matchedMentor = \App\Models\Mentor::find(session('matched'));
    @endphp
    @if($matchedMentor)
        <div class="match-popup">
            <div class="match-content">
                <div class="match-icon">👥</div>
                <h2>Congratulations!</h2>
                <p>You’ve successfully matched with 
                   {{ $matchedMentor->first_name }} {{ $matchedMentor->last_name }}!</p>
                <button onclick="this.closest('.match-popup').style.display='none'">
                    Close
                </button>
            </div>
        </div>
    @endif
@endif

@if(session('info'))
    <div class="match-popup">
        <div class="match-content">
            <div class="match-icon">⚠️</div>
            <h2>Notice</h2>
            <p>{{ session('info') }}</p>
            <button onclick="this.closest('.match-popup').style.display='none'">
                Close
            </button>
        </div>
    </div>
@endif


  </main>
</div>

</body>
</html>
