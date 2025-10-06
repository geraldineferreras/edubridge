<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('video-room.{room_id}', function ($user, $room_id) {
    return [
        'id'   => $user->id,
        'name' => $user->first_name . ' ' . $user->last_name,
        'role' => $user instanceof \App\Models\Mentor ? 'mentor' : 'mentee',
    ];
});
