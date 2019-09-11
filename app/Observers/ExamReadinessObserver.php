<?php

namespace App\Observers;

use App\ExampReadiness;
use App\Status;

class ExamReadinessObserver
{
    /**
     * Handle the activity "created" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function created(ExampReadiness $ExampReadiness)
    {
        $log = actlog('Membuat kesiapan ujian.');
        $status = Status::byName('Created')->first();
        $ExampReadiness->status()->attach($status->id, [
            'log_id' => $log,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Handle the activity "updated" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function updated(Activity $activity)
    {
        $this->saveStatus($activity, 'Edited', 'Mengubah pengajuan activity.');
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

    /**
     * Save status
     * 
     * @param  \App\Activity  $activity
     * @param  string  $status
     * @param  string  $desc
     */
    public function saveStatus($activity, $status, $desc)
    {
        $log = actlog($desc);
        $status = Status::byName($status)->first();
        $activity->status()->attach($status->id, [
            'log_id' => $log,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
