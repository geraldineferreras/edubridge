<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mentor Dashboard - Calendar</title>
  <link rel="stylesheet" href="{{ asset('css/mentor-d-calendar.css') }}" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
<div class="dashboard">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="logo">
      <img src="{{ asset('img/logo.png') }}" />
    </div>
    <nav>
      <a href="{{ route('mentor.dashboard') }}"><span class="material-icons">home</span>Home</a>
      <a href="{{ route('mentor.applications') }}"><span class="material-icons">assignment</span>Application</a>
      <a href="{{ route('mentor.sessions.index') }}"><span class="material-icons">computer</span>Session</a>
      <a class="active" href="{{ route('mentor.calendar') }}"><span class="material-icons">calendar_today</span>Calendar</a>
      <a href="{{ route('mentor.messages') }}"><span class="material-icons">chat</span>Messages</a>
    </nav>
  </aside>

  <!-- Main content -->
  <main class="content">
    <header class="top-bar">
      <div class="breadcrumbs">Calendar ></div>
      <div class="top-icons">
        <span class="material-icons">notifications</span>
        <span class="material-icons">account_circle</span>
      </div>
    </header>

    <!-- Calendar Section -->
    <section class="calendar-section">
      <div class="calendar-header">
        <select>
          <option>All Sessions</option>
        </select>
        <div class="month-nav">
          <span class="material-icons" id="prevMonth">chevron_left</span>
          <h2 id="currentMonthYear">August 2025</h2>
          <span class="material-icons" id="nextMonth">chevron_right</span>
        </div>
      </div>

      <!-- Calendar grid -->
      <div class="calendar-grid" id="calendarDays"></div>
    </section>
  </main>
</div>

<!-- Modal -->
<div id="scheduleModal" class="modal hidden">
  <div class="modal-content">
    <span id="closeModalBtn" class="material-icons close-btn">close</span>
    <h3 id="modalTitle">Schedule a Session</h3>

    <form id="scheduleForm" method="POST" action="{{ route('mentor.schedule.store') }}">
      @csrf
      <input type="hidden" name="date" id="scheduleDate">
      <input type="hidden" name="schedule_id" id="scheduleId">

      <label>Time</label>
      <input type="time" name="time" id="scheduleTime" required>

      <label>Course</label>
      <input type="text" name="course" id="scheduleCourse" placeholder="Course name" required>

      <div class="form-actions">
    <button type="submit" id="saveBtn">Save</button>
    <button type="button" onclick="closeModal()">Cancel</button>
  </div>
</form>

<!-- Pass schedules and routes to JS -->
<div id="calendarData" data-schedules='@json($schedules)'></div>

<script>
  window.routes = {
    store: @json(route('mentor.schedule.store')),
    updateTemplate: @json(route('mentor.schedule.update', ['id' => '__ID__']))
  };
</script>

<script src="{{ asset('js/mentor-calendar.js') }}"></script>
</body>
</html>
