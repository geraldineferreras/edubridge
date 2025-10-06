<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="{{ asset('css/reset-password.css') }}">
</head>
<body>
  <div class="card">
    <h2>Reset your Password</h2>
    <p>Check your email for the 4-digit code</p>

    @if ($errors->any())
      <div style="color: red;">
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('password.verify.code') }}">
      @csrf
      <input type="hidden" name="email" value="{{ old('email', session('reset_email')) }}">
      <input type="text" name="code" placeholder="4-digit code" required />
      <input type="password" name="password" placeholder="New Password" required />
      <input type="password" name="password_confirmation" placeholder="Confirm Password" required />
      <button class="btn" type="submit">Confirm</button>
    </form>
  </div>
</body>
</html>
