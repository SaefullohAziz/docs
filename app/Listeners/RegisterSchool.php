<?php

namespace App\Listeners;

use App\School;
use App\Events\SchoolRegistered;
use App\Notifications\SchoolCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterSchool
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
        $this->sendNotification($event->school, $event->password);
    }

    /**
     * Send notification
     * 
     * @param  \App\Training  $school
     */
    public function sendNotification($school, $password)
    {
        $school = School::findOrFail($school->id);
        $school->notify(new SchoolCreated($school, $password));
    }
}
