<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mentor Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/mentor-d-application.css') }}" />
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
        <a href="{{ route('mentor.dashboard') }}"><span class="material-icons">home</span>Home</a>
        <a class="active" href="{{ route('mentor.applications') }}"><span class="material-icons">assignment</span>Application</a>
        <a href="{{ route('mentor.sessions.index') }}"><span class="material-icons">computer</span>Session</a>
        <a href="{{ route('mentor.calendar') }}"><span class="material-icons">calendar_today</span>Calendar</a>
        <a href="{{ route('mentor.messages') }}"><span class="material-icons">chat</span>Messages</a>
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

      <h2 class="page-title">Mentee Applicants</h2>

      <div class="table-section">
        <h3>Pending Mentee Requests</h3>
        <table>
          <thead>
            <tr>
              <th>id</th>
              <th>display name</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pendingApplications as $index => $app)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td><strong>{{ $app->mentee->first_name }} {{ $app->mentee->last_name }}</strong></td>
              <td>
                <div class="action-buttons">
                  <form action="{{ route('mentor.application.update', [$app->id, 'accepted']) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="accept-btn">Accept</button>
                  </form>

                  <form action="{{ route('mentor.application.update', [$app->id, 'declined']) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="decline-btn">Decline</button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach

            @if($pendingApplications->isEmpty())
            <tr>
              <td colspan="3" style="text-align:center;">No pending applications</td>
            </tr>
            @endif
          </tbody>
        </table>
      </div>
    </main>
  </div>
</body>
</html>
