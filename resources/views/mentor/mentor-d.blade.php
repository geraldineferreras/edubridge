<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mentor Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/mentor-d.css') }}" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">
        <img src="{{ asset('img/logo.png') }}" alt="Logo"/>
      </div>
      <nav>
        <a class="active" href="{{ route('mentor.dashboard') }}"><span class="material-icons">home</span>Home</a>
        <a href="{{ route('mentor.applications') }}"><span class="material-icons">assignment</span>Application</a>
        <a href="{{ route('mentor.sessions.index') }}"><span class="material-icons">computer</span>Session</a>
        <a href="{{ route('mentor.calendar') }}"><span class="material-icons">calendar_today</span>Calendar</a>
        <a href="{{ route('mentor.messages') }}"><span class="material-icons">chat</span>Messages</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="content">
      <header class="top-bar">
        <div class="breadcrumbs">Home ></div>
        <div class="top-icons">
          <span class="material-icons">notifications</span>
          <span class="material-icons">account_circle</span>
        </div>
      </header>

      <section class="welcome">
  <h1>Welcome, {{ $mentor->first_name }}!</h1>
  <p>Start connecting with mentees and get ready to take your career to the next level!</p>
</section>

<section class="stats-cards">
  <div class="stats-card">
    <p><strong>Mentee request</strong></p>
    <p>+{{ $menteeRequests }}</p>
    <a href="{{ route('mentor.applications') }}">View details</a>
  </div>
  <div class="stats-card">
    <p><strong>Total people</strong></p>
    <p>+{{ $totalMentees }}</p>
  </div>
  <div class="stats-card">
    <p><strong>Active people</strong></p>
    <p>+{{ $activeMentees }}</p>
  </div>
</section>

<section class="progress-section">
  <div class="progress-item">
    <div class="progress-circle"><span>{{ $ongoingSessions }}</span></div>
    <p>Ongoing Session</p>
  </div>
  <div class="progress-item">
    <div class="progress-circle"><span>{{ $completedSessions }}</span></div>
    <p>Completed Sessions</p>
  </div>
</section>


    </main>
  </div>
</body>
</html>
