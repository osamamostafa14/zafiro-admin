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


// Broadcast::channel('chat', function ($user) {
//     // Replace with your actual logic to determine if the user can listen to the private channel
//     return auth()->check();
// });

Broadcast::channel('conversations.{conversationId}', function ($user, $conversationId) {
    // Add your authorization logic here
    // Example: Check if the user is part of the conversation
    return true; // Replace with your actual authorization logic
}); 


// Broadcast::channel('conversation.{conversation_id}', function ($conversation, int $conversation_id) {
//     return $conversation->id === $conversation_id;
// });