<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Mentor Registration Complete</title>
  <link rel="stylesheet" href="{{ asset('css/mentor-register.css') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="mentor-body">
  <div class="mentor-container">
    <div class="success-box">
      <h2>🎉 Registration Successful!</h2>
      <p>Thank you for registering as a mentor. We’ll review your profile shortly.</p>
      <a class="btn" href="{{ route('login') }}">Go to Login</a>
    </div>
  </div>
</body>
</html>