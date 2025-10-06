<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mentor Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/mentor-d-session.css') }}" />
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
        <a href="{{ route('mentor.dashboard') }}"><span class="material-icons">home</span>Home</a>
        <a href="{{ route('mentor.applications') }}"><span class="material-icons">assignment</span>Application</a>
        <a class="active" href="{{ route('mentor.sessions.index') }}"><span class="material-icons">computer</span>Session</a>
        <a href="{{ route('mentor.calendar') }}"><span class="material-icons">calendar_today</span>Calendar</a>
        <a href="{{ route('mentor.messages') }}"><span class="material-icons">chat</span>Messages</a>
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
        @foreach($sessions as $session)
          @php
            $isFull = $session->participants_count >= $session->capacity;
            $remaining = $session->capacity - $session->participants_count;
          @endphp

          <!-- ✅ Whole card clickable if meet_link exists -->
          @if($session->meet_link && !$isFull)
            <a href="{{ $session->meet_link }}" target="_blank" class="card group-session-card link-card" style="text-decoration: none; color: inherit;">
              <div class="session-banner">
                <span class="status-badge">{{ $remaining }} Slots</span>
                <h2>{{ $session->title }}</h2>
                <p class="hosted-text">Hosted on <span class="brand">Google Meet</span></p>
              </div>

              <div class="session-info">
                <p class="session-time">{{ $session->start_time->format('M d, g:ia') }}</p>
                <p class="session-title">{{ $session->description }}</p>
                <div class="mentor-meta">
                  <img src="{{ $session->mentor->profile_photo ? asset('storage/'.$session->mentor->profile_photo) : asset('img/default-mentor.png') }}" alt="Mentor"/>
                  <div>
                    <p class="mentor-name">{{ $session->mentor->first_name }} {{ $session->mentor->last_name }}</p>
                    <p class="mentor-role">{{ $session->mentor->role }}</p>
                  </div>
                </div>
              </div>
            </a>
          @else
            <!-- Disabled / Full / No link -->
            <div class="card group-session-card disabled" style="opacity: 0.7; cursor: not-allowed;">
              <div class="session-banner">
                <span class="status-badge">{{ $isFull ? 'Full' : 'No Meet Link' }}</span>
                <h2>{{ $session->title }}</h2>
                <p class="hosted-text">Hosted on <span class="brand">Google Meet</span></p>
              </div>

              <div class="session-info">
                <p class="session-time">{{ $session->start_time->format('M d, g:ia') }}</p>
                <p class="session-title">{{ $session->description }}</p>
                <div class="mentor-meta">
                  <img src="{{ $session->mentor->profile_photo ? asset('storage/'.$session->mentor->profile_photo) : asset('img/default-mentor.png') }}" alt="Mentor"/>
                  <div>
                    <p class="mentor-name">{{ $session->mentor->first_name }} {{ $session->mentor->last_name }}</p>
                    <p class="mentor-role">{{ $session->mentor->role }}</p>
                  </div>
                </div>
              </div>
            </div>
          @endif
        @endforeach
      </div>

      <!-- Create Session Button -->
      <button class="create-session-btn" onclick="openCreateModal()">
        <span class="material-icons">add</span>
        Create Session
      </button>
    </main>
  </div>

  <!-- Modal 1: Choose Session Type -->
  <div id="createModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">Create a Session</div>
      <div class="modal-options">
        <button class="create-later" onclick="openLaterModal()"><span class="material-icons">event</span> Create Meeting for Later</button>

        <!-- ✅ Instant Meeting Button -->
        <button class="instant" type="button" onclick="createInstantMeeting()">
          <span class="material-icons">flash_on</span> Start an Instant Meeting
        </button>

        <button class="schedule"><span class="material-icons">calendar_today</span> Schedule in Calendar</button>
      </div>
      <button class="close-btn" onclick="closeCreateModal()">Close</button>
    </div>
  </div>

  <!-- Modal 2: Create Meeting for Later -->
  <div id="laterModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">Create Meeting for Later</div>

      <form id="sessionForm" action="{{ route('mentor.sessions.store') }}" method="POST" class="session-form">
        @csrf
        <input type="hidden" name="mentor_id" value="{{ auth('mentor')->id() }}">

        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" id="title" name="title" placeholder="Enter session title" required>
        </div>

        <div class="form-group">
          <label for="description">Description</label>
          <textarea id="description" name="description" rows="4" placeholder="Write a short description..."></textarea>
        </div>

        <div class="form-group">
          <label for="start_time">Start Time</label>
          <input type="datetime-local" id="start_time" name="start_time" required>
        </div>

        <div class="form-group">
          <label for="end_time">End Time</label>
          <input type="datetime-local" id="end_time" name="end_time" required>
        </div>

        <div class="form-group">
          <label for="capacity">Capacity</label>
          <input type="number" id="capacity" name="capacity" min="1" placeholder="Number of slots" required>
        </div>

        <button type="submit" class="submit-btn">
          <span class="material-icons">check</span> Create Session
        </button>
      </form>

      <button class="close-btn" onclick="closeLaterModal()">Cancel</button>
    </div>
  </div>

  <script>
    // Modal functions
    function openCreateModal() {
      document.getElementById('createModal').style.display = 'flex';
    }
    function closeCreateModal() {
      document.getElementById('createModal').style.display = 'none';
    }
    function openLaterModal() {
      document.getElementById('createModal').style.display = 'none';
      document.getElementById('laterModal').style.display = 'flex';
    }
    function closeLaterModal() {
      document.getElementById('laterModal').style.display = 'none';
    }

    // Close when clicking outside
    window.onclick = function(event) {
      if (event.target.id === "createModal") closeCreateModal();
      if (event.target.id === "laterModal") closeLaterModal();
    }

    // Set min datetime = now
    (function(){
      const startTimeInput = document.getElementById('start_time');
      if(startTimeInput){
        const now = new Date().toISOString().slice(0,16);
        startTimeInput.min = now;
      }
    })();

    // ✅ Instant Meeting -> backend generates Google Meet link
    async function createInstantMeeting() {
      try {
        const resp = await fetch("{{ route('mentor.sessions.instant') }}", {
          method: "POST",
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({})
        });
        const data = await resp.json();
        if (data.meet_link) {
          closeCreateModal();
          window.location.href = data.meet_link;
        } else {
          alert('Could not create instant meeting');
        }
      } catch (err) {
        console.error(err);
        alert('Error creating instant meeting');
      }
    }
  </script>
</body>
</html>
