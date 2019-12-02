<?php

namespace App\Listeners;

use App\School;
use App\Events\TrainingRegistered;
use App\Notifications\TrainingApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterTraining
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
     * @param  TrainingRegistered  $event
     * @return void
     */
    public function handle(TrainingRegistered $event)
    {
        $this->sendNotification($event->training);
    }

    /**
     * Send notification
     * 
     * @param  \App\Training  $training
     */
    public function sendNotification($training)
    {
        $school = School::findOrFail($training->school->id);
        $school->notify(new TrainingApproved($training));
    }
}
