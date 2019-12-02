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
        
    }

    /**
     * Handle the school "created" event.
     *
     * @param  \App\School  $school
     * @return void
     */
    public function created(School $school)
    {
        // $this->createAccount($school);
        // $this->sendNotification($school);
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
     * Create school's account
     * 
     * @param  \App\Training  $school
     */
    public function createAccount($school)
    {
        $school->user()->create([
            'name' => 'User', 
            'email' => $school->pic[0]->email, 
            'password' => \Illuminate\Support\Facades\Hash::make('!Indo45!Joss!'),
        ]);
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
