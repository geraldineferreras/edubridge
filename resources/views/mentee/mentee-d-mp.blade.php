<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $mentor->first_name }} {{ $mentor->last_name }} - Mentor Profile</title>

  <!-- Fonts & Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/mentee-d-mp.css') }}">
</head>
<body>

  <!-- Header -->
  <header>
    <span class="brand">
      <img src="{{ asset('img/logo.png') }}" alt="EduBridge Logo" />
    </span>
    <div class="icons">
      <i class="fas fa-user-circle"></i>
    </div>
  </header>

  <div class="container">

    <!-- Mentor Profile -->
    <div class="profile">
      <img src="{{ $mentor->profile_photo ? asset('storage/' . $mentor->profile_photo) : asset('img/default.png') }}" class="profile-img" alt="{{ $mentor->first_name }} {{ $mentor->last_name }}">
      <div class="name">{{ $mentor->first_name }} {{ $mentor->last_name }}</div>
      <div class="role">{{ $mentor->role ?? 'Mentor' }}</div>
      <div class="specialty">{{ $mentor->category ?? 'N/A' }}</div>

      <div class="details">
        <div><i class="fas fa-location-dot"></i> {{ $mentor->location ?? 'N/A' }}</div>
        <div><i class="fas fa-clock"></i> Active Today</div>
        <div><i class="fas fa-star"></i> {{ number_format($mentor->average_rating ?? 0, 1) }} ({{ $mentor->total_reviews }} review{{ $mentor->total_reviews == 1 ? '' : 's' }})</div>
        <div><i class="fas fa-check-double"></i> Usually Responds in half a day</div>
      </div>

      <div class="skills">
        <h4>Skills</h4>
        @foreach($mentor->skills as $skill)
          <span class="skill-badge">{{ $skill }}</span>
        @endforeach
      </div>

      <div class="about">
        <h4>About</h4>
        <p>{{ $mentor->bio ?? 'No description available.' }}</p>
      </div>

      <!-- Feedback Section -->
      <div class="feedback">
        <h4>Feedback</h4>

        @if(session('success'))
          <div class="success-msg">{{ session('success') }}</div>
        @endif

        <form class="feedback-form" method="POST" action="{{ route('mentor.feedback.store', $mentor->id) }}">
          @csrf
          <textarea name="comment" placeholder="Write your feedback..." required></textarea>
          <select name="rating">
            <option value="">Rate (optional)</option>
            @for($i=1;$i<=5;$i++)
              <option value="{{ $i }}">{{ $i }} ★</option>
            @endfor
          </select>
          <button type="submit">Submit Feedback</button>
        </form>

        @forelse($mentor->feedbacks as $feedback)
          <div class="feedback-item">
            <p><strong>{{ $feedback->author }}</strong>
            @if($feedback->rating)
              @for($i=0;$i<$feedback->rating;$i++) ★ @endfor
            @endif
            <br>
            "{{ $feedback->comment }}"
            </p>
          </div>
        @empty
          <p>No feedback available yet.</p>
        @endforelse
      </div>

      <div class="btn-wrapper">
  <a href="{{ route('mentee.chat', $mentor->id) }}" class="btn">Message</a>
</div>

    </div>

    <!-- Mentorship Plan -->
    <div class="plan">
      <h2>Mentorship Plan</h2>
      <div class="plan-price">${{ $mentor->price ?? 0 }} <span>/ per month</span></div>
      <div class="plan-details">{{ $mentor->plan_details ?? 'Details not provided.' }}</div>

      <div class="feature"><i class="fas fa-phone"></i> 2 calls per month (30min/call)</div>
      <div class="feature"><i class="fas fa-comments"></i> Unlimited Q&A via chat</div>
      <div class="feature"><i class="fas fa-clock"></i> Expect responses in 2 days</div>
      <div class="feature"><i class="fas fa-dumbbell"></i> Hands-on support</div>

      <!-- Apply Form -->
      <div class="btn-wrapper2">
    @if($mentor->already_applied)
        <button class="btn2" disabled>
            {{ ucfirst($mentor->application_status) }}
        </button>
    @else
        <form id="applyForm" method="POST" action="{{ route('apply.store') }}">
            @csrf
            <input type="hidden" name="mentor_id" value="{{ $mentor->id }}">
            <button type="submit" class="btn2">Apply</button>
        </form>
    @endif
</div>

    </div>
  </div>

  <!-- Success Modal -->
  <div id="success-modal" class="modal" style="display:none;">
    <div class="modal-content">
      <h2>🎉 Congratulations!</h2>
      <p>You've successfully applied. Please wait for the mentor's confirmation.</p>
      <button class="okay-btn" onclick="closeModal()">Okay</button>
    </div>
  </div>

  <script>
    const applyForm = document.getElementById('applyForm');
    if(applyForm) {
      applyForm.addEventListener('submit', function(e){
        e.preventDefault();
        const formData = new FormData(this);

        fetch(this.action, {
          method: "POST",
          headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
          },
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if(data.success){
            document.getElementById('success-modal').style.display = 'flex';
            // Disable the button immediately after success
            applyForm.querySelector('button').textContent = 'Already Applied';
            applyForm.querySelector('button').disabled = true;
          } else {
            alert('Failed to apply!');
          }
        })
        .catch(err => alert('Error: ' + err));
      });
    }

    function closeModal(){
      document.getElementById('success-modal').style.display = 'none';
    }

    window.onclick = function(event){
      const modal = document.getElementById('success-modal');
      if(event.target === modal) modal.style.display = 'none';
    };
  </script>

</body>
</html>
