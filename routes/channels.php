<?php

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

use Illuminate\Support\Facades\Log;

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Room.{uuid}', function ($user, $uuid) {
    $room = $user->getRoom();

    if (!$room) {
        Log::warning('User ' . e($user->name) . ' (no room) tried to authorize in room ' . $uuid);
        return false;
    }

    if ((string) $room->uuid === (string) $uuid) {
        return true;
    } else {
        Log::warning('User ' . e($user->name) . ' (Room ' . $room->uuid . ') tried to authorize in room ' . $uuid);
        return false;
    }
});
