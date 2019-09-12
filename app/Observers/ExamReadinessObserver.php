<?php

namespace App\Observers;

use App\ExamReadiness;
use App\Status;

class ExamReadinessObserver
{
    /**
     * Handle the activity "created" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function created(ExamReadiness $examReadiness)
    {
        $this->saveStatus($examReadiness, 'Created', 'Membuat kesiapan ujian.');
    }

    /**
     * Handle the activity "updated" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function updated(ExamReadiness $examReadiness)
    {
        $this->saveStatus($examReadiness, 'Edited', 'Mengubah kesiapan ujian.');
    }

    /**
     * Handle the activity "deleted" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function deleted(ExamReadiness $examReadiness)
    {
        //
    }

    /**
     * Handle the activity "restored" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function restored(ExamReadiness $examReadiness)
    {
        //
    }

    /**
     * Handle the activity "force deleted" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function forceDeleted(ExamReadiness $examReadiness)
    {
        //
    }

    /**
     * Save status
     * 
     * @param  \App\ExamReadiness  $examReadiness
     * @param  string  $status
     * @param  string  $desc
     */
    public function saveStatus($examReadiness, $status, $desc)
    {
        $log = actlog($desc);
        $status = Status::byName($status)->first();
        $examReadiness->status()->attach($status->id, [
            'log_id' => $log,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
