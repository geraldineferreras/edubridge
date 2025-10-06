<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EduBridge Chat</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/mentee-d-chatbox.css') }}">
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
        <a href="{{ url('mentee/applications') }}"><span class="material-icons">assignment</span>Application</a>
        <a href="{{ route('mentee.session') }}"><span class="material-icons">computer</span>Session</a>
        <a href="{{ route('mentee.calendar') }}"><span class="material-icons">calendar_today</span>Calendar</a>
        <a class="active" href="{{ route('mentee.messages') }}"><span class="material-icons">chat</span>Messages</a>
        <a href="{{ route('mentee.matchmaking') }}"><span class="material-icons">diversity_3</span>Match mentors</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="content">
      <header class="top-bar">
        <div class="breadcrumbs">Messages > Chat Box ></div>
        <div class="top-icons">
          <span class="material-icons">notifications</span>
          <span class="material-icons">account_circle</span>
        </div>
      </header>

      <h2 class="page-title">Chatbox</h2>

      <!-- Chat Window -->
      <div class="chat-container">

        <!-- Chat Header with Mentor Profile -->
        <div class="chat-header">
          <img src="{{ $conversation->mentor->profile_photo 
              ? asset('storage/' . $conversation->mentor->profile_photo) 
              : asset('images/default-mentor.png') }}" 
     alt="Mentor" class="mentor-avatar">

          <div class="mentor-info">
            <h3>{{ $conversation->mentor->first_name }} {{ $conversation->mentor->last_name }}</h3>
            <p>{{ $conversation->mentor->expertise ?? 'Mentor' }}</p>
          </div>
        </div>

        <!-- Messages -->
        <div class="chat-messages">
    @foreach ($messages as $msg)
        <div class="message {{ $msg->isFromMentee() ? 'sent' : 'received' }}">
            <p>
                <strong>{{ $msg->isFromMentee() ? 'You' : $conversation->mentor->first_name }}:</strong>
                {{ $msg->body }}
            </p>
            <span class="time">{{ $msg->created_at->timezone('Asia/Manila')->format('g:i A') }}</span>
        </div>
    @endforeach
</div>


        <!-- Chat Input -->
        <!-- Chat Input -->
<form action="{{ route('mentee.chat.send', $conversation->id) }}" method="POST" class="chat-input">
    @csrf
    <input type="text" name="body" placeholder="Type your message..." required class="chat-text">
    <button type="submit" class="chat-send">Send</button>
</form>


      </div>
    </main>
  </div>
</body>
</html>
