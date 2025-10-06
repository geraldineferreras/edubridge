<?php 

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RegisterMenteeController;
use App\Http\Controllers\MentorRegisterController;
use App\Http\Controllers\Auth\MenteeLoginController;
use App\Http\Controllers\Auth\MentorLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\CustomPasswordResetController;
use App\Http\Controllers\MenteeDashboardController;
use App\Http\Controllers\MentorDashboardController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\MenteeController;
use App\Http\Controllers\MenteeSessionController;
use App\Http\Controllers\MenteeCalendarController;
use App\Http\Controllers\MenteeMessageController;
use App\Http\Controllers\MentorScheduleController;
use App\Http\Controllers\MenteeScheduleController;
use App\Http\Controllers\MentorMessageController;
use App\Http\Controllers\GroupSessionController;
use App\Http\Controllers\VideoCallController;
use App\Http\Controllers\GoogleAuthController;

// 🏠 Public Home
Route::get('/', fn () => view('index'));

// 👤 Mentee Registration
Route::get('/register', fn () => view('auth.register'));
Route::post('/register', [RegisterMenteeController::class, 'store'])->name('register');

// 🧑‍🏫 Mentor Registration
Route::get('/mentor/register', function () {
    Session::forget(['success', 'current_step']);
    return view('auth.mentor-register');
})->name('mentor.register');
Route::post('/mentor/register', [MentorRegisterController::class, 'store'])->name('mentor.register.submit');

// ✅ Mentor Congrats
Route::get('/mentor/congrats', fn () => view('auth.mentor-congrats'))->name('mentor.congrats');

// 🔐 Login Form
Route::get('/login', fn () => view('auth.login'))->name('login');

// 🔓 Login Handling
Route::post('/mentee/login', [MenteeLoginController::class, 'login'])->name('mentee.login.submit');
Route::post('/login/mentor', [MentorLoginController::class, 'login'])->name('mentor.login.submit');

// 📤 Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 📲 Google OAuth
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.auth');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('google.callback');

// Example route to test event creation with Google Meet
Route::get('/google/create-event', [GoogleAuthController::class, 'createEvent'])->name('google.create.event');

// 🔑 Password Reset (Custom 4-digit Code Flow)
Route::get('/forgot-password', [CustomPasswordResetController::class, 'showEmailForm'])->name('password.request');
Route::post('/forgot-password', [CustomPasswordResetController::class, 'sendCode'])->name('password.email.code');
Route::get('/reset-password/code', [CustomPasswordResetController::class, 'showCodeForm'])->name('password.code.form');
Route::post('/reset-password/code', [CustomPasswordResetController::class, 'verifyAndReset'])->name('password.verify.code');

// 📝 Mentee Routes (Protected by auth:mentee)
Route::middleware(['auth:mentee'])->group(function () {
    Route::get('/mentee/dashboard', [MenteeDashboardController::class, 'index'])->name('mentee.dashboard');
    Route::put('/mentee/profile', [MenteeController::class, 'updateProfile'])->name('mentee.profile.update');
    Route::get('/mentee/session', [MenteeSessionController::class, 'index'])->name('mentee.session');
    Route::get('/mentee/calendar', [MenteeScheduleController::class, 'index'])->name('mentee.calendar');

    // 📚 Group Sessions (mentee side)
    Route::get('/mentee/sessions', [GroupSessionController::class, 'menteeSessions'])->name('mentee.sessions.index');
    Route::post('/mentee/sessions/{id}/join', [GroupSessionController::class, 'join'])->name('mentee.sessions.join');

    // Messaging
    Route::get('/mentee/messages', [MenteeMessageController::class, 'index'])->name('mentee.messages');
    Route::get('/mentee/chat/{mentor}', [MenteeMessageController::class, 'chat'])->name('mentee.chat');
    Route::post('/mentee/conversation/{conversation}/send', [MenteeMessageController::class, 'send'])->name('mentee.chat.send');
    Route::get('/mentee/messages/{conversation}', [MenteeMessageController::class, 'show'])->name('mentee.messages.show');

    // Browse Mentors
    Route::get('/mentee/browse-mentors', [MenteeController::class, 'browseMentors'])->name('mentee.browse.mentors');
    Route::get('/mentee/mentor/{id}', [MenteeController::class, 'showProfile'])->name('mentee.mentor.show');

    // Applications
    Route::get('/mentee/applications', [MenteeController::class, 'applications'])->name('mentee.applications');

    // Store Feedback
    Route::post('/mentor/{id}/feedback', [MenteeController::class, 'storeFeedback'])->name('mentor.feedback.store');

    // Apply to Mentor (Manual)
    Route::post('/apply', [ApplicationController::class, 'store'])->name('apply.store');

    // Matchmaking
    Route::get('/mentee/matchmaking', [MenteeController::class, 'matchmaking'])->name('mentee.matchmaking');
    Route::post('/mentee/matchmaking/find', [MenteeController::class, 'findBestMentor'])->name('mentee.matchmaking.find');
    Route::post('/mentee/matchmaking/apply', [MenteeController::class, 'applyMatch'])->name('mentee.matchmaking.apply');
});

// 🧑‍🏫 Mentor Routes (Protected by auth:mentor)
Route::prefix('mentor')->middleware(['auth:mentor'])->group(function () {
    Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('mentor.dashboard');

    // ✅ Mentor Applications Routes
    Route::get('/applications', [ApplicationController::class, 'index'])->name('mentor.applications');
    Route::post('/application/{id}/{status}', [ApplicationController::class, 'updateStatus'])->name('mentor.application.update');

    // 📚 Group Sessions
    Route::get('/sessions', [GroupSessionController::class, 'index'])->name('mentor.sessions.index');
    Route::post('/sessions', [GroupSessionController::class, 'store'])->name('mentor.sessions.store');
    Route::post('/sessions/{id}/join', [GroupSessionController::class, 'join'])->name('mentor.sessions.join');

    // ⚡ Instant Session
    Route::post('/sessions/instant', [GroupSessionController::class, 'instant'])->name('mentor.sessions.instant');

    // 📅 Mentor Calendar Routes
    Route::get('/calendar', [MentorScheduleController::class, 'index'])->name('mentor.calendar');
    Route::post('/calendar', [MentorScheduleController::class, 'store'])->name('mentor.schedule.store');
    Route::put('/calendar/{id}', [MentorScheduleController::class, 'update'])->name('mentor.schedule.update');

    // 💬 Mentor Messaging Routes
    Route::get('/messages', [MentorMessageController::class, 'index'])->name('mentor.messages');
    Route::get('/messages/{conversation}', [MentorMessageController::class, 'show'])->name('mentor.messages.show');
    Route::post('/messages/{conversation}/send', [MentorMessageController::class, 'send'])->name('mentor.chat.send');

    // 🎥 Video Calls (mentor)
    Route::get('/sessions/{room_id}/video', [VideoCallController::class, 'index'])->name('sessions.video');
    Route::post('/sessions/{room_id}/signal', [VideoCallController::class, 'signal']);
    Route::post('/sessions/{room_id}/chat', [VideoCallController::class, 'chat']);
});

// 📹 Video Call Routes (Accessible by both mentees & mentors)
Route::middleware(['auth:mentee,mentor'])->group(function () {
    Route::get('/video/{room}', [VideoCallController::class, 'join'])->name('video.join');
});

// ✅ Define a "home" route (used by GoogleAuthController)
Route::get('/home', function () {
    if (Auth::guard('mentee')->check()) {
        return redirect()->route('mentee.dashboard');
    } elseif (Auth::guard('mentor')->check()) {
        return redirect()->route('mentor.dashboard');
    }
    return view('index'); // fallback to landing page if not logged in
})->name('home');
