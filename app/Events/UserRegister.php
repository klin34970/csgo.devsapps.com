<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\User;

class UserRegister
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user, $ip, $support, $country;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $ip, $support, $country)
    {
        $this->user = $user;
        $this->ip = $ip;
        $this->support = $support;
        $this->country = $country;
    }

    public function broadcastOn(){
        return new Channel('users');
    }

    public function broadcastAs() {
        return 'login';
    }
}
