<?php

namespace App\Listeners\UserLogin;

use App\Events\UserLogin;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\UserConnection;

class Log implements ShouldQueue
{
    public $queue = 'log';

    /**
     * Handle the event.
     *
     * @param  UserLogin  $event
     * @return void
     */
    public function handle(UserLogin $event)
    {
        UserConnection::create([
            'user_id'   => $event->user->id,
            'ip'        => $event->ip,
            'support'   => $event->support,
            'country'   => $event->country,
        ]);
        logger()->channel('activities')->info('User login', ['user' => $event]);
    }
}
