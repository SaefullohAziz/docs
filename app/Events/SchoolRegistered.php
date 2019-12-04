<?php

namespace App\Events;

use App\School;
use Faker\Factory as Faker;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SchoolRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $school;
    public $password;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(School $school)
    {
        $this->school = $school;
        $faker = Faker::create();
        $this->password = $faker->password;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
