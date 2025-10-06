<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EduBridge Sign Up</title>
  <link rel="stylesheet" href="{{ asset('css/register.css') }}" />
</head>
<body class="register-body">
  <div class="register-container">
    <div class="register-box">
      <div class="form-section">
        <h1>Sign Up <span class="role">as a Mentee</span></h1>

        <form class="signup-form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
          @csrf

  <div class="photo-section">
    <img id="profilePreview" src="https://via.placeholder.com/100" alt="Preview" />
    <label class="custom-file-btn">
      Choose Photo
      <input type="file" name="profile_picture" id="profilePic" accept="image/*" onchange="previewImage(event)" hidden>
    </label>
  </div>

          <input type="text" name="first_name" placeholder="First Name" required />
          <input type="text" name="middle_name" placeholder="Middle Name" />
          <input type="text" name="last_name" placeholder="Last Name" required />
          <input type="email" name="email" placeholder="Email" required />
          <input type="password" name="password" placeholder="Password" required />
          
          <div class="profile-fields">
  <input type="number" name="age" placeholder="Age" min="10" max="100" class="age-field" required />
  <div class="location-field">
    <select name="location" required class="location-select">
      <option disabled selected>Location</option>
      <option>Angeles City</option>
      <option>Apalit</option>
      <option>Arayat</option>
      <option>Bacolor</option>
      <option>Candaba</option>
      <option>Floridablanca</option>
      <option>Guagua</option>
      <option>Lubao</option>
      <option>Mabalacat</option>
      <option>Macabebe</option>
      <option>Magalang</option>
      <option>Masantol</option>
      <option>Mexico</option>
      <option>Minalin</option>
      <option>Porac</option>
      <option>San Fernando</option>
      <option>San Luis</option>
      <option>San Simon</option>
      <option>Santo Tomas</option>
      <option>Santa Ana</option>
      <option>Sasmuan</option>
    </select>
  </div>
  <input type="text" name="skills" placeholder="Preferred Skills / Expertise" class="skills-field" />
</div>

          <div class="gender-selection">
            <label>
              <input type="radio" name="gender" value="Male" /> Male
            </label>
            <label>
              <input type="radio" name="gender" value="Female" /> Female
            </label>
          </div>

          <!-- 🔹 Submit Button -->
          <button type="submit" class="btn">Sign Up</button>

          <div class="divider"><span>or</span></div>

          <a href="{{ url('/auth/google') }}" class="google-btn">
            <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" />
            Sign up with Google
          </a>
        </form>

        <p class="terms">
          By clicking "Sign Up" or "Sign up with Google" you accept the
          <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
        </p>
        <p class="redirect">
          Already have an account? <a href="{{ url('/login') }}">Login</a><br />
          Looking to join as a mentor? <a href="#">Apply Now</a>
        </p>
      </div>
    </div>
  </div>
  
  <script>
  function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
      const output = document.getElementById('profilePreview');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  }
</script>

</body>
</html>