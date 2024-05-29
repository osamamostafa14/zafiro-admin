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

Broadcast::channel('chat', function ($user) {
    // Replace with your actual logic to determine if the user can listen to the private channel
    return auth()->check();
});


// Broadcast::channel('conversation.{conversation_id}', function ($conversation, int $conversation_id) {
//     return $conversation->id === $conversation_id;
// });