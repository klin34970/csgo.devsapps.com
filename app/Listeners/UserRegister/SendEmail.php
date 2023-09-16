<?php

namespace App\Listeners\UserRegister;

use App\Events\UserRegister;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail
{
    public $queue = 'mail';

    /**
     * Handle the event.
     *
     * @param  UserRegister  $event
     * @return void
     */
    public function handle(UserRegister $event)
    {

    }
}
