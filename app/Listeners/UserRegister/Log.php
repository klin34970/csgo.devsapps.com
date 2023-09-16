<?php

namespace App\Listeners\UserRegister;

use App\Events\UserRegister;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\UserConnection;

class Log implements ShouldQueue
{
    public $queue = 'log';

    /**
     * Handle the event.
     *
     * @param  UserRegister  $event
     * @return void
     */
    public function handle(UserRegister $event)
    {
        UserConnection::create([
            'user_id'   => $event->user->id,
            'ip'        => $event->ip,
            'support'   => $event->support,
            'country'   => $event->country,
        ]);
        logger()->channel('activities')->info('User register', ['user' => $event]);
    }
}
