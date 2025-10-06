<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Browse Mentors</title>
  <link rel="stylesheet" href="{{ asset('css/mentee-d-bm.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    /* Make the whole card clickable */
    .card-link {
      text-decoration: none;
      color: inherit;
      display: block;
    }

    .card {
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
      transform: scale(1.03);
      box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }
  </style>
</head>
<body>
  <header>
    <img src="{{ asset('img/logo.png') }}" alt="Logo" />
    <nav class="main-nav">
      <a href="{{ route('mentee.browse.mentors') }}" class="nav-link active">Browse Mentors</a>
      <a href="#" class="nav-link">Group Session</a>
    </nav>
    <div class="profile-icon">
      <i class="fas fa-user-circle"></i>
    </div>
  </header>

  <!-- 🔎 Search -->
  <div class="search-container">
    <form method="GET" action="{{ route('mentee.browse.mentors') }}">
      <div class="search-box">
        <label for="mentor-search">
          <i class="fas fa-search search-icon"></i>
        </label>
        <input 
          type="text" 
          id="mentor-search" 
          name="search" 
          value="{{ request('search') }}" 
          placeholder="example teaching C++" 
        />
      </div>
    </form>
  </div>

  <!-- 🏷 Categories -->
  <div class="categories">
    <div><i class="fa-brands fa-html5"></i> HTML</div>
    <div><i class="fa-brands fa-css3-alt"></i> CSS</div>
    <div><i class="fa-brands fa-js"></i> JavaScript</div>
    <div><i class="fa-brands fa-python"></i> Python</div>
    <div><i class="fa-solid fa-code"></i> C</div>
    <div><i class="fa-solid fa-code"></i> C++</div>
    <div><i class="fa-brands fa-java"></i> Java</div>
    <div><i class="fa-solid fa-database"></i> SQL</div>
    <div><i class="fa-solid fa-terminal"></i> Bash</div>
    <div><i class="fa-solid fa-gears"></i> Rust</div>
    <div><i class="fa-solid fa-g"></i> Go</div>
    <div><i class="fa-brands fa-php"></i> PHP</div>
    <div><i class="fa-brands fa-laravel"></i> Laravel</div>
    <div><i class="fa-brands fa-react"></i> React</div>
    <div><i class="fa-brands fa-vuejs"></i> Vue</div>
    <div><i class="fa-brands fa-node-js"></i> Node.js</div>
    <div><i class="fa-brands fa-angular"></i> Angular</div>
    <div><i class="fa-solid fa-file-code"></i> TypeScript</div>
  </div>

  <!-- 👨‍🏫 Mentor Cards -->
  <div class="cards">
    @forelse($mentors as $mentor)
      <a href="{{ route('mentee.mentor.show', $mentor->id) }}" class="card-link">
        <div class="card">
          <!-- Profile Image -->
          <img 
            src="{{ $mentor->profile_photo ? asset('storage/' . $mentor->profile_photo) : asset('img/default.png') }}" 
            alt="Mentor" 
            class="mentor-image" 
          />

          <div class="card-info">
            <!-- Name -->
            <strong>{{ $mentor->first_name }} {{ $mentor->last_name }}</strong> 

            <!-- Expertise -->
            <p>{{ $mentor->skills ?? $mentor->category ?? 'Not specified' }}</p>

            <!-- Experience -->
            <p><b>Experience:</b> {{ $mentor->years_experience ?? 0 }} yrs</p>

            <!-- Stats -->
            <div class="card-details">
              <div>{{ $mentor->sessions_count ?? 0 }} Sessions</div>
              <div>
    @php
        $avg = round($mentor->feedbacks_avg_rating ?? 0);
    @endphp

    @for($i = 1; $i <= 5; $i++)
        <i class="fas fa-star" style="color: {{ $i <= $avg ? 'orange' : 'gray' }}"></i>
    @endfor

    ({{ number_format($mentor->feedbacks_avg_rating ?? 0, 1) }}) 
    {{ $mentor->feedbacks_count ?? 0 }} Reviews
</div>

            </div>
          </div>
        </div>
      </a>
    @empty
      <p>No mentors found.</p>
    @endforelse
  </div>

  <!-- Pagination -->
  <div class="btn-wrapper">
    {{ $mentors->links() }}
  </div>

  <script>
  const scrollContainer = document.querySelector('.categories');
  if (scrollContainer) {
    scrollContainer.addEventListener('wheel', function (e) {
      if (e.deltaY !== 0) {
        e.preventDefault();
        scrollContainer.scrollLeft += e.deltaY;
      }
    });
  }
  </script>
</body>
</html>
