<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EduBridge</title>

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Stylesheet -->
  <link rel="stylesheet" href="{{ asset('css/index.css') }}"/>
</head>
<body>

  <!-- Header -->
  <header>
    <div class="logo">
      <img src="{{ asset('img/logo.png') }}" alt="EduBridge Logo" />
    </div>
    <nav>
      <ul>
        <li><a href="#home">Home</a></li>
        <li><a href="#mentor">Mentors</a></li>
        <li><a href="#about">About Us</a></li>
        <li><a href="#contact">Contact Us</a></li>      
        <li><a href="#help">Help Center</a></li>
        <li><a href="#developers">Developers</a></li>
        <li><a href="{{ url('login') }}">Log in</a></li>
      </ul>
    </nav>
  </header>

  <!-- Home Section -->
  <section class="home-section" id="home">
    <main class="hero">
      <div class="hero-text">
        <h1>Welcome to <span>EduBridge</span> –<br>Your Gateway to Collaborative Learning and Mentorship</h1>
        <p>
          Unlock your potential through hands-on learning, real-time coding sessions, and expert guidance. 
          EduBridge connects aspiring learners and mentors using an AI-powered matching solution that pairs 
          users based on skills, goals, and interests.
        </p>
        <p class="cta-text">Start your mentorship journey today and join a new era of peer-driven education.</p>
        <a href="{{ url('login') }}" class="btn">Join Now!</a>
      </div>
      <div class="hero-img">
        <img src="{{ asset('img/1.png') }}" alt="Mentor Illustration"/>
      </div>
    </main>
  </section>

  <!-- Mentor Section -->
  <section class="mentor-section" id="mentor">
    <div class="mentor-header">
      <div>
        <h3 class="subtitles">Find your Mentor</h3>
        <h1 class="titles">Explore Mentor</h1>
        <p>View all job profiles alphabetically and filter by category.</p>
      </div>
      <div class="search-box">
        <label for="mentor-search">
          <i class="fas fa-search search-icon"></i>
        </label>
        <input type="text" id="mentor-search" placeholder="example teaching C++" />
      </div>
    </div>

    <div class="mentor-cards">
      @for ($i = 0; $i < 12; $i++)
      <div class="mentor-card">
        <img src="{{ asset('img/2.png') }}" alt="Mentor" />
        <h4>Mentor Name</h4>
        <p>Specialty</p> 
      </div>
      @endfor
    </div>
  </section>

  <!-- About Us Section -->
  <section class="about-section" id="about">
    <div class="about-container">
      <div class="about-text">
        <h3 class="subtitle">About Us</h3>
        <p>
          Our platform was created to enhance peer-to-peer mentorship in programming and technical education by integrating live coding, project collaboration, and AI-powered mentor matching.
        </p>

        <div class="mission-vision">
          <div class="mission">
            <h2>Our Mission</h2>
            <p>To empower learners by fostering a collaborative, peer-driven environment enhanced with real-time live coding, intelligent mentor-mentee matching, and hands-on project collaboration—building both technical expertise and soft skills essential for success in the modern tech industry.</p>
          </div>
          <div class="vision">
            <h2>Our Vision</h2>
            <p>To be a leading platform that revolutionizes coding education through innovative mentorship models, combining the power of community, interactivity, and AI.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Us Section -->
  <section class="contact-section" id="contact">
    <div class="contact-container">
      <div class="contact-text">
        <h3 class="subtitle">Contact Us</h3>
      </div>

      <div class="contact-content">
        <div class="contact-details">
          <h1 class="title">Get in Touch</h1>
          <p><i class="fas fa-phone"></i> +63 912 345 6789</p>
          <p><i class="fab fa-facebook"></i> <a href="https://www.facebook.com/edubridge" target="_blank">@EduBridge</a></p>
          <p><i class="fab fa-instagram"></i> <a href="https://www.instagram.com/edubridge" target="_blank">@EDUBRIDGE</a></p>
          <p><i class="fab fa-twitter"></i> <a href="https://twitter.com/edubridge" target="_blank">@EB2025</a></p>
          <p><i class="fas fa-envelope"></i> <a href="mailto:edubridge@gmail.com" target="_blank">edubridge@gmail.com</a></p>
        </div>

        <div class="contact-form-container">
          <form class="contact-form">
            <input type="text" placeholder="Your Name" required />
            <input type="email" placeholder="Your Email" required />
            <input type="text" placeholder="Subject" />
            <textarea placeholder="Your Message" rows="5" required></textarea>
            <div class="submit">
              <button type="submit" class="btn">Send Message</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Help Section -->
  <section class="help-section" id="help">
    <h3 class="subtitle">Help Center</h3>

    <section class="info-cards-section">
      <div class="cards-container">
        <div class="info-card">
          <img src="{{ asset('img/getting started.png') }}" alt="Card 1" class="card-image" />
          <h3>Getting Started</h3>
        </div>
        <div class="info-card">
          <img src="{{ asset('img/customize profile.png') }}" alt="Card 2" class="card-image" />
          <h3>Customize Profile</h3>
        </div>
        <div class="info-card">
          <img src="{{ asset('img/chatbot.png') }}" alt="Card 3" class="card-image" />
          <h3>Chat Bot</h3>
        </div>
      </div>
    </section>

    <div class="help-container">
      <h1 class="title">Frequently Asked Questions</h1>

      <div class="faq-item">
        <button class="faq-question">How do I find a mentor?</button>
        <div class="faq-answer">
          <p>Use the Explore Mentor section to browse profiles and filter by skills or categories.</p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">Can I become a mentor?</button>
        <div class="faq-answer">
          <p>Yes! Sign up and apply to become a mentor through your profile dashboard.</p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">Is there a cost to use EduBridge?</button>
        <div class="faq-answer">
          <p>EduBridge is free to use for learners and mentors alike.</p>
        </div>
      </div>

      <div class="faq-item">
        <button class="faq-question">How do live coding sessions work?</button>
        <div class="faq-answer">
          <p>Once matched with a mentor, you can schedule and join live coding sessions directly through the platform.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Developers Section -->
  <section class="team-section" id="developers">
    <h2 class="team-title">Meet Our Team</h2>
    <div class="team-cards">
      @php
        $team = [
          ['img' => 'rein.png', 'name' => 'Garcia, Colette Reinier R.', 'role' => 'Programmer'],
          ['img' => 'gab.png', 'name' => 'Ikan, Gabriel Kim Andrei S.', 'role' => 'Project Leader'],
          ['img' => 'win.png', 'name' => 'Angeles, Carlo Arwin T.', 'role' => 'Document Planner'],
          ['img' => 'lorie.png', 'name' => 'Samera, Lorie Mae C.', 'role' => 'Research Specialist'],
          ['img' => 'jen.png', 'name' => 'Pascua, Jennalyn L.', 'role' => 'UI/UX Designer'],
          ['img' => 'leona.png', 'name' => 'Manalang, Leona May M.', 'role' => 'Graphic Designer'],
          ['img' => 'marky.png', 'name' => 'Singian, Marky', 'role' => 'Project Support'],
        ];
      @endphp

      @foreach($team as $member)
      <div class="team-card">
        <img src="{{ asset('img/' . $member['img']) }}" alt="{{ $member['name'] }}">
        <h4>{{ $member['name'] }}</h4>
        <p>{{ $member['role'] }}</p>
      </div>
      @endforeach
    </div>
  </section>

  <!-- Script for FAQ toggle -->
  <script>
    document.querySelectorAll('.faq-question').forEach(btn => {
      btn.addEventListener('click', () => {
        btn.parentElement.classList.toggle('active');
      });
    });
  </script>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-about">
        <img src="{{ asset('img/logo.png') }}" alt="EduBridge Logo" class="footer-logo" />
        <p>Built with passion by the EduBridge Development Team</p>
      </div>
      <div class="footer-links">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#mentor">Mentors</a></li>
          <li><a href="#about">About Us</a></li>
          <li><a href="#contact">Contact Us</a></li>
          <li><a href="#help">Help Center</a></li>
          <li><a href="#developers">Developers</a></li>
        </ul>
      </div>
      <div class="footer-contact">
        <h4>Contact</h4>
        <p><i class="fas fa-phone"></i> +63 912 345 6789</p>
        <p><i class="fab fa-facebook"></i> <a href="https://www.facebook.com/edubridge" target="_blank">@EduBridge</a></p>
        <p><i class="fab fa-instagram"></i> <a href="https://www.instagram.com/edubridge" target="_blank">@EDUBRIDGE</a></p>
        <p><i class="fab fa-twitter"></i> <a href="https://twitter.com/edubridge" target="_blank">@EB2025</a></p>
        <p><i class="fas fa-envelope"></i> <a href="mailto:edubridge@gmail.com" target="_blank">edubridge@gmail.com</a></p>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 EduBridge. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>