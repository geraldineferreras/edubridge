<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EduBridge - Messages</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/mentee-d-message.css') }}">
</head>
<body>
  <div class="dashboard">
    <aside class="sidebar">
      <div class="logo">
        <img src="{{ asset('img/logo.png') }}" alt="Logo">
      </div>
      <nav>
        <a href="{{ url('mentee/dashboard') }}"><span class="material-icons">home</span>Home</a>
        <a href="{{ url('mentee/applications') }}"><span class="material-icons">assignment</span>Application</a>
        <a href="{{ url('mentee/session') }}"><span class="material-icons">computer</span>Session</a>
        <a href="{{ url('mentee/calendar') }}"><span class="material-icons">calendar_today</span>Calendar</a>
        <a class="active" href="{{ route('mentee.messages') }}"><span class="material-icons">chat</span>Messages</a>
        <a href="{{ route('mentee.matchmaking') }}"><span class="material-icons">diversity_3</span>Match mentors</a>
      </nav>
    </aside>

    <main class="content">
      <header class="top-bar">
        <div class="breadcrumbs">Messages ></div>
        <div class="top-icons">
          <span class="material-icons">notifications</span>
          <span class="material-icons">account_circle</span>
        </div>
      </header>

      <h2 class="page-title">Messages</h2>

      <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
        <div class="search-box small-search">
          <span class="material-icons search-icon">search</span>
          <input type="text" placeholder="Search Messages..." />
        </div>
      </div>

      <div class="messages-container">
        @forelse ($conversations as $conv)
    @php
        $last = $conv->latestMessage; // may be null
        $snippet = $last ? \Illuminate\Support\Str::limit($last->body, 80) : 'No messages yet.';
        $time = $last ? $last->created_at->timezone('Asia/Manila')->diffForHumans() : '';
        $mentorName = $conv->mentor->first_name . ' ' . $conv->mentor->last_name;
    @endphp

    <a href="{{ route('mentee.messages.show', $conv) }}" style="text-decoration: none; color: inherit;">
        <div class="message-card">
            <div class="avatar"><span class="material-icons">person</span></div>
            <div class="message-content">
                <div class="message-header">
                    <h3>{{ $mentorName }}</h3>
                    <span class="message-time">{{ $time }}</span>
                </div>
                <p class="message-snippet">{{ $snippet }}</p>
            </div>
        </div>
    </a>
@empty
    <p style="opacity:.8">No conversations yet.</p>
@endforelse

      </div>
    </main>
  </div>
</body>
</html>
