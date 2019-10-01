<?php

namespace App\Observers;

use App\School;
use App\Notifications\SchoolCreated;

class SchoolObserver
{
    /**
     * Handle the school "creating" event.
     *
     * @param  \App\School  $school
     * @return void
     */
    public function creating(School $school)
    {
        // $school->code = mt_rand(1000000, 9999999);
    }

    /**
     * Handle the school "created" event.
     *
     * @param  \App\School  $school
     * @return void
     */
    public function created(School $school)
    {
        $this->sendNotification($school);
    }

    /**
     * Handle the school "updated" event.
     *
     * @param  \App\School  $school
     * @return void
     */
    public function updated(School $school)
    {
        //
    }

    /**
     * Handle the school "deleted" event.
     *
     * @param  \App\School  $school
     * @return void
     */
    public function deleted(School $school)
    {
        //
    }

    /**
     * Handle the school "restored" event.
     *
     * @param  \App\School  $school
     * @return void
     */
    public function restored(School $school)
    {
        //
    }

    /**
     * Handle the school "force deleted" event.
     *
     * @param  \App\School  $school
     * @return void
     */
    public function forceDeleted(School $school)
    {
        //
    }

    /**
     * Send notification
     * 
     * @param  \App\Training  $school
     */
    public function sendNotification($school)
    {
        $school = School::findOrFail($school->id);
        $school->notify(new SchoolCreated($school));
    }
}
