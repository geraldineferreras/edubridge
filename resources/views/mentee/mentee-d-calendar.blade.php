<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mentee Calendar</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/mentor-d-calendar.css') }}"> {{-- same CSS as mentor --}}
</head>
<body>
  <div class="dashboard">
    <aside class="sidebar">
      <div class="logo"><img src="{{ asset('img/logo.png') }}" alt="Logo"></div>
      <nav>
        <a href="{{ url('mentee/dashboard') }}"><span class="material-icons">home</span>Home</a>
        <a href="{{ url('mentee/applications') }}"><span class="material-icons">assignment</span>Application</a>
        <a href="{{ url('mentee/session') }}"><span class="material-icons">computer</span>Session</a>
        <a class="active" href="{{ route('mentee.calendar', ['year' => $firstOfMonth->year, 'month' => $firstOfMonth->month]) }}">
          <span class="material-icons">calendar_today</span>Calendar
        </a>
        <a href="{{ route('mentee.messages') }}"><span class="material-icons">chat</span>Messages</a>
        <a href="{{ route('mentee.matchmaking') }}"><span class="material-icons">diversity_3</span>Match mentors</a>
      </nav>
    </aside>

    <main class="content">
      <header class="top-bar">
        <div class="breadcrumbs">Calendar ></div>
        <div class="top-icons">
          <span class="material-icons">notifications</span>
          <span class="material-icons">account_circle</span>
        </div>
      </header>

      <section class="calendar-section">
        <div class="calendar-header">
          <select><option>All Sessions</option></select>
          <div class="month-nav">
            <a href="{{ route('mentee.calendar', ['year' => $prev->year, 'month' => $prev->month]) }}">
              <span class="material-icons">chevron_left</span>
            </a>
            <h2>{{ $firstOfMonth->format('F Y') }}</h2>
            <a href="{{ route('mentee.calendar', ['year' => $next->year, 'month' => $next->month]) }}">
              <span class="material-icons">chevron_right</span>
            </a>
          </div>
        </div>

        <div class="calendar-grid">
          {{-- Day name headers --}}
          @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $dn)
            <div class="day-name">{{ $dn }}</div>
          @endforeach

          {{-- Empty cells before the 1st --}}
          @for ($i = 0; $i < $startWeekday; $i++)
            <div class="day empty"></div>
          @endfor

          {{-- Actual days --}}
          @foreach ($days as $day)
            <div class="day">
              <div class="day-number">{{ $day->format('j') }}</div>

              {{-- Sessions --}}
              @foreach ($schedules as $schedule)
                @if (\Carbon\Carbon::parse($schedule->date)->isSameDay($day))
                  <div class="session">
                    {{ $schedule->course }} with 
                    {{ $schedule->mentor->first_name ?? 'Unknown' }} 
                    {{ $schedule->mentor->last_name ?? '' }}
                    ({{ \Carbon\Carbon::parse($schedule->time)->format('g:i A') }})
                  </div>
                @endif
              @endforeach
            </div>
          @endforeach
        </div>
      </section>
    </main>
  </div>
</body>
</html>
