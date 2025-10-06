<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GoogleAuthController extends Controller
{
    /**
     * Step 1: Redirect user to Google Login
     */
    public function redirectToGoogle()
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->setRedirectUri(url('/auth/google/callback'));
        $client->setAccessType('offline'); // ✅ needed for refresh token
        $client->setPrompt('consent');     // ✅ force consent so refresh_token is returned

        return redirect($client->createAuthUrl());
    }

    /**
     * Step 2: Handle Google OAuth Callback
     */
    public function handleGoogleCallback(Request $request)
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setRedirectUri(url('/auth/google/callback'));

        // 🔍 Step 1: Check if Google returned "code"
        if (!$request->has('code')) {
            return response()->json([
                'error' => 'No code returned from Google',
                'query' => $request->all()
            ]);
        }

        // 🔍 Step 2: Exchange code for token
        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        if (isset($token['error'])) {
            return response()->json([
                'error'   => 'Failed to get access token',
                'details' => $token
            ]);
        }

        // 🔍 Step 3: Ensure refresh_token is stored
        if (!isset($token['refresh_token'])) {
            $existingTokenPath = storage_path('app/google/token.json');
            if (file_exists($existingTokenPath)) {
                $existingToken = json_decode(file_get_contents($existingTokenPath), true);
                $token['refresh_token'] = $existingToken['refresh_token'] ?? null;
            }
        }

        // 🔍 Step 4: Save token.json
        Storage::disk('local')->put('google/token.json', json_encode($token, JSON_PRETTY_PRINT));

        // 🔍 Step 5: Return JSON success
        return response()->json([
            'success' => true,
            'message' => 'Google Calendar linked successfully!',
            'token'   => $token
        ]);
    }

    /**
     * Step 3: Example – Create a Calendar Event with Meet Link
     */
    public function createEvent()
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->addScope(Google_Service_Calendar::CALENDAR);

        if (!Storage::exists('google/token.json')) {
            return redirect()->route('google.auth')->with('error', 'Please connect Google first.');
        }

        $accessToken = json_decode(Storage::get('google/token.json'), true);
        $client->setAccessToken($accessToken);

        if ($client->isAccessTokenExpired()) {
            return redirect()->route('google.auth')->with('error', 'Google token expired. Please reconnect.');
        }

        $service = new Google_Service_Calendar($client);

        $event = new Google_Service_Calendar_Event([
            'summary' => 'Mentorship Session',
            'start' => [
                'dateTime' => '2025-10-05T10:00:00+08:00',
                'timeZone' => 'Asia/Manila',
            ],
            'end' => [
                'dateTime' => '2025-10-05T11:00:00+08:00',
                'timeZone' => 'Asia/Manila',
            ],
            'conferenceData' => [
                'createRequest' => [
                    'requestId' => uniqid(),
                    'conferenceSolutionKey' => ['type' => 'hangoutsMeet'],
                ],
            ],
        ]);

        $event = $service->events->insert('primary', $event, ['conferenceDataVersion' => 1]);

        return response()->json([
            'success'   => true,
            'meet_link' => $event->hangoutLink,
        ]);
    }
}
