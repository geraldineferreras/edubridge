<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mentee Applications | EduBridge</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/mentee-d-application.css') }}">
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
        <a href="{{ route('mentee.applications') }}" class="{{ request()->routeIs('mentee.applications') ? 'active' : '' }}"><span class="material-icons">assignment</span>Application</a>
        <a href="{{ url('mentee/session') }}"><span class="material-icons">computer</span>Session</a>
        <a href="{{ route('mentee.calendar') }}"><span class="material-icons">calendar_today</span>Calendar</a>
        <a href="{{ route('mentee.messages') }}"><span class="material-icons">chat</span>Messages</a>
        <a href="{{ route('mentee.matchmaking') }}"><span class="material-icons">diversity_3</span>Match mentors</a>

      </nav>
    </aside>

    <!-- Main Content -->
    <main class="content">
      <header class="top-bar">
        <div class="breadcrumbs">Application ></div>
        <div class="top-icons">
          <span class="material-icons">notifications</span>
          <span class="material-icons">account_circle</span>
        </div>
      </header>

      <h2 class="page-title">Applications</h2>

      <!-- Status Dashboard -->
      <section class="status-dashboard">
    <div class="status-card pending">
        <h3>Pending</h3>
        @foreach($applications->where('status', 'pending') as $application)
            <p>
                <span class="mentor-name">{{ $application->mentor->first_name }} {{ $application->mentor->last_name }}</span> <br>
                <a href="{{ route('mentee.mentor.show', $application->mentor_id) }}" class="view-details">
                    View Details
                </a>
            </p>
        @endforeach
    </div>

    <div class="status-card processing">
        <h3>Processing</h3>
        @foreach($applications->where('status', 'processing') as $application)
            <p>
                <span class="mentor-name">{{ $application->mentor->first_name }} {{ $application->mentor->last_name }}</span> <br>
                <a href="{{ route('mentee.mentor.show', $application->mentor_id) }}" class="view-details">
                    View Details
                </a>
            </p>
        @endforeach
    </div>

    <div class="status-card completed">
        <h3>Completed</h3>
        @foreach($applications->where('status', 'completed') as $application)
            <p>
                <span class="mentor-name">{{ $application->mentor->first_name }} {{ $application->mentor->last_name }}</span> <br>
                <a href="{{ route('mentee.mentor.show', $application->mentor_id) }}" class="view-details">
                    View Details
                </a>
            </p>
        @endforeach
    </div>
</section>


      <!-- Button to Browse Mentors -->
      <div class="btn-wrapper">
        <a href="{{ route('mentee.browse.mentors') }}" class="btn">Find Mentors</a>
      </div>
    </main>
  </div>
</body>
</html>
