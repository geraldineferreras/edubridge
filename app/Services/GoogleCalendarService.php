<?php

namespace App\Services;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Carbon\Carbon;

class GoogleCalendarService
{
    private $client;
    private $service;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path('app/google/credentials.json'));
        $this->client->addScope(Google_Service_Calendar::CALENDAR);
        $this->client->setAccessType('offline');

        $tokenPath = storage_path('app/google/token.json');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($accessToken);

            // 🔄 Refresh token if expired
            if ($this->client->isAccessTokenExpired()) {
                $newToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                if (!empty($newToken['refresh_token'])) {
                    $accessToken['refresh_token'] = $newToken['refresh_token'];
                }
                file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
            }
        }

        $this->service = new Google_Service_Calendar($this->client);
    }

    public function createEvent($title, $description, $startTime, $endTime = null)
{
    $start = \Carbon\Carbon::parse($startTime, 'Asia/Manila')->toRfc3339String();
    $end = $endTime
        ? \Carbon\Carbon::parse($endTime, 'Asia/Manila')->toRfc3339String()
        : \Carbon\Carbon::parse($startTime, 'Asia/Manila')->addHour()->toRfc3339String();

    $event = new \Google_Service_Calendar_Event([
        'summary'     => $title,
        'description' => $description,
        'start' => [
            'dateTime' => $start,
            'timeZone' => 'Asia/Manila',
        ],
        'end' => [
            'dateTime' => $end,
            'timeZone' => 'Asia/Manila',
        ],
        'conferenceData' => [
            'createRequest' => [
                'requestId' => uniqid(),
            ],
        ],
    ]);

    $createdEvent = $this->service->events->insert(
        'primary',
        $event,
        ['conferenceDataVersion' => 1]
    );

    return [
        'meet_link' => $createdEvent->hangoutLink ?? null,
        'event_id'  => $createdEvent->id,
    ];
}

}
