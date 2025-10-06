<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Apply as a Mentor</title>
  <link rel="stylesheet" href="{{ asset('css/mentor-register.css') }}" />
</head>
<body class="mentor-body">

  <div class="mentor-container">

    <!-- Step Navigation using Radio -->
    <input type="radio" name="step" id="step1" {{ session('success') ? '' : 'checked' }}>
<input type="radio" name="step" id="step2">
<input type="radio" name="step" id="step3">
<input type="radio" name="step" id="step4" {{ session('success') ? 'checked' : '' }}>

    <!-- Header -->
    <header class="mentor-header">
      <h2>Apply as a Mentor</h2>
      <div class="steps">
        <label for="step1" id="label-step1" class="step">
          <span class="step-circle" id="circle-step1">1</span>
          <span class="step-title">About You</span>
        </label>
        <label for="step2" id="label-step2" class="step">
          <span class="step-circle" id="circle-step2">2</span>
          <span class="step-title">Profile</span>
        </label>
        <label for="step3" id="label-step3" class="step">
          <span class="step-circle" id="circle-step3">3</span>
          <span class="step-title">Experience</span>
        </label>
      </div>
    </header>

    <!-- Begin Laravel Form -->
    <form action="{{ route('mentor.register') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <input type="hidden" id="current_step_input" name="current_step" value="step1">

      <!-- Step 1: About You -->
      <div class="form-page page1">
        <p class="info-box">Lovely to see you!<br><br>
          Filling out the form only takes a couple minutes. We'd love to learn more about your background and the ins-and-outs of why you'd like to become a mentor. Keep things personal and talk directly to us and your mentees. We don't need jargon and polished cover letters here!
          You agree to our code of conduct and the mentor agreement by sending the form, so be sure to have a look at those.</p>

        <div class="form-grid">
          <div class="photo-section">
            <img id="profilePreview" src="https://via.placeholder.com/100" alt="Preview" />
            <label class="custom-file-btn">
              Choose Photo
              <input type="file" name="profile_photo" id="profilePic" accept="image/*" onchange="previewImage(event)" hidden>
            </label>
          </div>

          <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" required>
          </div>
          <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
</div>
<div class="form-group">
          <label>Age</label>
  <input type="number" name="age" min="10" max="100" required>
</div>
          <div class="form-group">
  <label>Gender</label>
  <select name="gender" required>
    <option disabled selected>Select Gender</option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
  </select>
</div>
          <div class="form-group">
            <label>Choose a password</label>
            <input type="password" name="password" required>
          </div>
          <div class="form-group">
            <label>Job title</label>
            <input type="text" name="job_title">
          </div>
          <div class="form-group">
            <label>Company (optional)</label>
            <input type="text" name="company">
          </div>
          <div class="form-group location">
            <label>Location</label>
            <select name="location" required>
              <option disabled selected>Please Select...</option>
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
        </div>
       


        <div class="btn-row">
          <label for="step2" class="btn">Next</label>
        </div>
      </div>

      <!-- Step 2: Profile -->
      <div class="form-page page2">
        <div class="profile-step">
          <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" class="input-field">
              <option>Please Select...</option>
              <option value="ui-ux-design">UI/UX Design</option>
              <option value="programming">Programming / Software Development</option>
              <option value="data-science">Data Science / Analytics</option>
              <option value="product-management">Product Management</option>
              <option value="graphic-design">Graphic Design</option>
              <option value="digital-marketing">Digital Marketing</option>
              <option value="content-writing">Content Writing / Copywriting</option>
              <option value="business-strategy">Business Strategy</option>
              <option value="project-management">Project Management</option>
              <option value="cybersecurity">Cybersecurity</option>
              <option value="devops">DevOps / Cloud Engineering</option>
              <option value="mobile-development">Mobile App Development</option>
              <option value="qa-testing">Quality Assurance / Testing</option>
            </select>
          </div>

          <div class="form-group">
            <label for="skills">Skills</label>
            <input type="text" name="skills" id="skills" class="input-field" placeholder="Add new skills..." />
            <p class="note">
              Describe your expertise to connect with mentees who have similar interests.<br>
              Comma-separated list of your skills (keep it below 10). Mentees will use this to find you.
            </p>
          </div>

          <div class="form-group">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" class="input-field" rows="4"></textarea>
            <p class="note">
              Tell us (and your mentees) a little bit about yourself. Talk about yourself in the first person,<br>
              as if you'd directly talk to a mentee. This will be public.
            </p>
          </div>

          <div class="form-double">
            <div class="form-group">
              <label for="website">
                Personal website <span class="optional">(optional)</span>
              </label>
              <input type="text" id="website" name="website" class="input-field" placeholder="You can add your blog, GitHub profile or similar here" />
            </div>

            <div class="form-group">
              <label for="twitter">
                Twitter handle <span class="optional">(optional)</span>
              </label>
              <input type="text" id="twitter" name="twitter" class="input-field" placeholder='Omit the "@" – e.g. "dqmonn"' />
            </div>
          </div>

          <div class="btn-row">
            <label for="step1" class="btn back">Previous</label>
            <label for="step3" class="btn">Next</label>
          </div>
        </div>
      </div>

      <!-- Step 3: Experience -->
      <div class="form-page page3">
        <div class="form-group">
          <label>Years of Experience</label>
          <select name="years_experience" required>
            <option disabled selected>Select</option>
            <option>1-2 years</option>
            <option>3-5 years</option>
            <option>6+ years</option>
          </select>
        </div>

        <div class="form-group">
          <label>Relevant Skills</label>
          <input type="text" name="relevant_skills" placeholder="e.g., HTML, CSS, JavaScript">
        </div>

        <div class="form-group">
          <label>Industries Worked In</label>
          <input type="text" name="industries" placeholder="e.g., IT, Education, Healthcare">
        </div>

        <div class="form-group">
          <label>Previous Mentoring Experience</label>
          <textarea name="mentoring_experience" rows="3" placeholder="Describe your past mentoring experience"></textarea>
        </div>

        <div class="form-group">
          <label>Upload Resume (optional)</label>
          <div class="file-upload">
            <input type="file" name="resume" id="cvUpload" hidden>
            <label for="cvUpload" class="custom-file-upload">Upload File</label>
          </div>
        </div>

        <div class="form-group">
          <label>Notable Projects or Achievements</label>
          <input type="text" name="notable_projects" placeholder="Optional">
        </div>

        <div class="btn-row">
          <label for="step2" class="btn back">Previous</label>
          <button type="submit" class="btn">Submit</button>
        </div>
      </div>
    </form>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // ✅ Image Preview
    const previewImage = (event) => {
      const preview = document.getElementById('profilePreview');
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = () => {
          preview.src = reader.result;
        };
        reader.readAsDataURL(file);
      }
    };
    const fileInput = document.querySelector('input[name="profile_photo"]');
    if (fileInput) fileInput.addEventListener('change', previewImage);

    // ✅ Step logic
    const stepIds = ['step1', 'step2', 'step3'];
    const stepCircles = stepIds.map(id => document.getElementById('circle-' + id));
    const stepLabels = stepIds.map(id => document.getElementById('label-' + id));

    function updateSteps(currentIndex) {
      stepLabels.forEach((label, i) => {
        label.classList.remove('active', 'completed');
        stepCircles[i].textContent = i + 1;
      });

      for (let i = 0; i < stepIds.length; i++) {
        if (i < currentIndex) {
          stepLabels[i].classList.add('completed');
          stepCircles[i].textContent = '✔';
        } else if (i === currentIndex) {
          stepLabels[i].classList.add('active');
        }
      }
    }

    stepIds.forEach((stepId, index) => {
      const radio = document.getElementById(stepId);
      if (radio) {
        radio.addEventListener('change', () => updateSteps(index));
      }
    });

    // ✅ When Step 4 is shown
    const step4Radio = document.getElementById('step4');
    if (step4Radio) {
      step4Radio.addEventListener('change', () => {
        stepLabels.forEach((label, i) => {
          label.classList.remove('active');
          label.classList.add('completed');
          stepCircles[i].textContent = '✔';
        });
      });
    }

    // ✅ Automatically restore previously checked step from session
    const sessionStep = "{{ session('current_step') }}";
    if (sessionStep && document.getElementById(sessionStep)) {
      document.getElementById(sessionStep).checked = true;
      document.getElementById(sessionStep).dispatchEvent(new Event('change'));
    } else {
      // Default logic if no session
      const checkedStep = document.querySelector('input[name="step"]:checked');
      if (checkedStep) {
        const stepIndex = stepIds.indexOf(checkedStep.id);
        if (stepIndex >= 0) {
          updateSteps(stepIndex);
        }
      }
    }

    // ✅ Before submit, save current step to hidden input
    const form = document.querySelector('form');
    if (form) {
      form.addEventListener('submit', function () {
        const checkedStep = document.querySelector('input[name="step"]:checked');
        if (checkedStep) {
          const hiddenStepInput = document.getElementById('current_step_input');
          if (hiddenStepInput) {
            hiddenStepInput.value = checkedStep.id;
          }
        }
      });
    }
  });
</script>

</body>
</html>