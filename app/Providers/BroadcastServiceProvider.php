<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();

        // Broadcast::channel('conversations.{conversationId}', function ($user, $conversationId) {
        //     // Add your authorization logic here
        //     return true; // Or some condition to authorize the user
        // });

        require base_path('routes/channels.php');
    }
}
