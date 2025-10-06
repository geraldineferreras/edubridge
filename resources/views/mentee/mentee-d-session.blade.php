<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EduBridge - Mentor Session</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/mentee-d-session.css') }}">
</head>
<body>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">
        <img src="{{ asset('img/logo.png') }}" alt="Logo">
      </div>
      <nav>
        <a href="{{ route('mentee.dashboard') }}"><span class="material-icons">home</span>Home</a>
        <a href="{{ route('mentee.applications') }}"><span class="material-icons">assignment</span>Application</a>
        <a class="active" href="{{ url('mentee/session') }}"><span class="material-icons">computer</span>Session</a>
        <a href="{{ route('mentee.calendar') }}"><span class="material-icons">calendar_today</span>Calendar</a>
        <a href="{{ route('mentee.messages') }}"><span class="material-icons">chat</span>Messages</a>
        <a href="{{ route('mentee.matchmaking') }}"><span class="material-icons">diversity_3</span>Match mentors</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="content">
      <header class="top-bar">
        <div class="breadcrumbs">Session ></div>
        <div class="top-icons">
          <span class="material-icons">notifications</span>
          <span class="material-icons">account_circle</span>
        </div>
      </header>

      <h2 class="page-title">Session</h2>

      <div class="card-row">
        @forelse($sessions as $session)
          @php
            $isFull = $session->participants_count >= $session->capacity;
            $remaining = $session->capacity - $session->participants_count;
          @endphp

          <!-- ✅ Entire card acts as a button -->
          @if(!$isFull && $session->meet_link)
            <a href="{{ $session->meet_link }}" target="_blank" class="card group-session-card link-card" style="text-decoration: none; color: inherit;">
              <div class="session-banner">
                <span class="status-badge">{{ $remaining }} Slots</span>
                <h2>{{ $session->title }}</h2>
                <p class="hosted-text">Hosted on <span class="brand">EduBridge</span></p>
              </div>

              <div class="session-info">
                <p class="session-description">{{ $session->description }}</p>
                <div class="session-details">
                  <div class="mentor-meta">
                    <img src="{{ $session->mentor->profile_photo ? asset('storage/'.$session->mentor->profile_photo) : asset('img/default-mentor.png') }}" alt="Mentor"/>
                    <div>
                      <p class="mentor-name">{{ $session->mentor->first_name }} {{ $session->mentor->last_name }}</p>
                      <p class="mentor-role">{{ $session->mentor->role }}</p>
                    </div>
                  </div>
                  <p class="session-time">{{ \Carbon\Carbon::parse($session->start_time)->format('M d, g:ia') }}</p>
                </div>
              </div>
            </a>
          @else
            <!-- Disabled (Full) Card -->
            <div class="card group-session-card disabled" style="opacity: 0.7; cursor: not-allowed;">
              <div class="session-banner">
                <span class="status-badge">Full</span>
                <h2>{{ $session->title }}</h2>
                <p class="hosted-text">Hosted on <span class="brand">EduBridge</span></p>
              </div>

              <div class="session-info">
                <p class="session-description">{{ $session->description }}</p>
                <div class="session-details">
                  <div class="mentor-meta">
                    <img src="{{ $session->mentor->profile_photo ? asset('storage/'.$session->mentor->profile_photo) : asset('img/default-mentor.png') }}" alt="Mentor"/>
                    <div>
                      <p class="mentor-name">{{ $session->mentor->first_name }} {{ $session->mentor->last_name }}</p>
                      <p class="mentor-role">{{ $session->mentor->role }}</p>
                    </div>
                  </div>
                  <p class="session-time">{{ \Carbon\Carbon::parse($session->start_time)->format('M d, g:ia') }}</p>
                </div>
              </div>
            </div>
          @endif
        @empty
          <p>No sessions scheduled yet.</p>
        @endforelse
      </div>
    </main>
  </div>
</body>
</html>
