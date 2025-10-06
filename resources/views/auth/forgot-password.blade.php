<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="{{ asset('css/reset-password.css') }}">
</head>
<body>
  <div class="card">
    <h2>Forgot your password?</h2>
    <p>Enter your email and we’ll send a 4-digit code.</p>

    @if (session('status'))
      <div style="color: green;">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
      <div style="color: red;">
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('password.email.code') }}">
      @csrf
      <input type="email" name="email" placeholder="Your Email" required>
      <button class="btn" type="submit">Send Code</button>
    </form>
  </div>
</body>
</html>
