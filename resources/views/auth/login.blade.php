<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EduBridge Login</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- Custom Login CSS -->
  <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
</head>
<body class="login-body">
  <div class="login-container">
    <div class="login-box">
      <!-- Top bar: Logo + Home -->
      <div class="top-bar">
        <a href="{{ url('/') }}" class="logo">
            <img src="{{ asset('img/logo2.png') }}" alt="EduBridge Logo" class="logo-img" />
        </a>
        <a class="btn" href="{{ url('/') }}"><i class="fas fa-home"></i> Home</a>
      </div>

      <h1 class="login-title">Log in</h1>

      <!-- Tabs -->
      <input type="radio" name="role" id="mentee" checked hidden>
      <input type="radio" name="role" id="mentor" hidden>

      <div class="role-tabs">
        <label for="mentee" class="tab" id="mentee-tab">I'm a Mentee</label>
        <label for="mentor" class="tab" id="mentor-tab">I'm a Mentor</label>
      </div>

      <!-- Mentee Login Form -->
      <form class="login-form form-mentee" method="POST" action="{{ route('mentee.login.submit') }}">
  @csrf

  {{-- Show the error if login fails --}}
  @if ($errors->has('email'))
    <div style="color: red; margin-bottom: 10px;">
      {{ $errors->first('email') }}
    </div>
  @endif

  <!-- Email Input -->
  <div class="input-group">
    <span class="icon"><i class="fas fa-user"></i></span>
    <input type="text" placeholder="Username / Email" name="email" value="{{ old('email') }}" required />
  </div>

  <!-- Password Input -->
  <div class="input-group">
    <span class="icon"><i class="fas fa-lock"></i></span>
    <input type="password" placeholder="Password" name="password" required />
  </div>
  
        <div class="options">
          <label>
            <input type="checkbox" /> Remember me
          </label>
          <a href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <button type="submit" class="btn">Login</button>

        <p>Sign Up as Mentee? <a href="{{ url('/register') }}">Apply Now</a></p>

        <div class="divider"><span>or</span></div>

        <button type="button" class="google-btn">
          <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" />
          Log in with Google
        </button>
      </form>

      <!-- Mentor Login Form -->
<form class="login-form form-mentor" method="POST" action="{{ route('mentor.login.submit') }}">
  @csrf
  <div class="input-group">
    <span class="icon"><i class="fas fa-user"></i></span>
    <input type="text" placeholder="Mentor Email" name="email" required />
  </div>

  <div class="input-group">
    <span class="icon"><i class="fas fa-lock"></i></span>
    <input type="password" placeholder="Password" name="password" required />
  </div>

        <div class="options">
          <label>
            <input type="checkbox" /> Remember me
          </label>
          <a href="{{ route('password.request') }}">Forgot password?</a>

        </div>

        <button type="submit" class="btn">Login</button>

        <p>Sign up as Mentor? <a href="{{ route('mentor.register') }}">Apply Now</a></p>

        <div class="divider"><span>or</span></div>

        <button type="button" class="google-btn">
          <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" />
          Log in with Google
        </button>
      </form>
    </div>
  </div>
</body>
</html>