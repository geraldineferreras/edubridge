<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mentee Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/mentee-d.css') }}" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">
        <img src="{{ asset('img/logo.png') }}"/>
      </div>
      <nav>
        <a class="active" href="{{ route('mentee.dashboard') }}"><span class="material-icons">home</span>Home</a>
        <a href="{{ route('mentee.applications') }}"><span class="material-icons">assignment</span>Application</a>
        <a href="{{ route('mentee.session') }}"><span class="material-icons">computer</span>Session</a>
        <a href="{{ route('mentee.calendar') }}"><span class="material-icons">calendar_today</span>Calendar</a>
        <a href="{{ route('mentee.messages') }}"><span class="material-icons">chat</span>Messages</a>
        <a href="{{ route('mentee.matchmaking') }}"><span class="material-icons">diversity_3</span>Match mentors</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="content">
      <header class="top-bar">
  <div class="breadcrumbs">Home ></div>
  <div class="top-icons">
    <span class="material-icons">notifications</span>
@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
@endphp

@if($user && $user->profile_picture)
    <img 
        src="{{ asset('storage/profile_photos/' . $user->profile_picture) }}" 
        alt="Profile"
        style="width:40px; height:40px; border-radius:50%; object-fit:cover; cursor:pointer;"
    >
@else
    <span class="material-icons">account_circle</span>
@endif
  </div>
</header>


      <!-- Welcome Section -->
      <section class="welcome">
        <h1>Welcome, {{ $mentee->name }}!</h1>
        <p>Start connecting with mentors and get ready to take your career to the next level!</p>
      </section>

      <!-- Recommended Mentors -->
      <div class="recommend-header">
        <h2>Recommended for you</h2>
        <form method="GET" action="{{ route('mentee.dashboard') }}" class="search-box">
          <span class="material-icons search-icon">search</span>
          <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}" />
        </form>
      </div>

      <div class="mentor-cards">
        @foreach($mentors as $mentor)
          <a href="{{ route('mentee.mentor.show', $mentor->id) }}" class="mentor-card">
            <img 
              src="{{ $mentor->profile_photo ? asset('storage/' . $mentor->profile_photo) : asset('img/default-profile.png') }}" 
              alt="{{ $mentor->first_name }} {{ $mentor->last_name }}" 
            />
            <div class="mentor-info">
              <h4>{{ $mentor->first_name }} {{ $mentor->last_name }}</h4>
              <p>{{ $mentor->skills }}</p>
            </div>
          </a>
        @endforeach
      </div>

      <div class="btn-wrapper">
        <a href="{{ route('mentee.browse.mentors') }}" class="btn">Browse Mentors</a>
      </div>
    </main>
  </div>
</body>
</html>
