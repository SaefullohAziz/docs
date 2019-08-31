<?php

namespace App\Observers;

use App\Activity;

class ActivityObserver
{
    /**
     * Handle the activity "created" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function created(Activity $activity)
    {
        //
    }

    /**
     * Handle the activity "updated" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function updated(Activity $activity)
    {
        //
    }

    /**
     * Handle the activity "deleted" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function deleted(Activity $activity)
    {
        //
    }

    /**
     * Handle the activity "restored" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function restored(Activity $activity)
    {
        //
    }

    /**
     * Handle the activity "force deleted" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function forceDeleted(Activity $activity)
    {
        //
    }
}
