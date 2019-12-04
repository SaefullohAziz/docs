<?php

namespace App\Listeners;

use App\Events\SchoolRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateSchoolUserAccount
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SchoolRegistered  $event
     * @return void
     */
    public function handle(SchoolRegistered $event)
    {
        $event->school->user()->create([
            'name' => 'User', 
            'email' => $event->school->pic[0]->email, 
            'password' => \Illuminate\Support\Facades\Hash::make($event->password),
        ]);
    }
}
