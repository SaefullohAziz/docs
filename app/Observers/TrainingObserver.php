<?php

namespace App\Observers;

use App\School;
use App\Training;
use App\Payment;
use App\Status;
use App\Notifications\TrainingWaited;
use App\Notifications\TrainingApproved;

class TrainingObserver
{
    /**
     * Handle the training "created" event.
     *
     * @param  \App\Training  $training
     * @return void
     */
    public function created(Training $training)
    {
        saveStatus($training, 'Created', 'Mendaftar program training.');
    }

    /**
     * Handle the training "updated" event.
     *
     * @param  \App\Training  $training
     * @return void
     */
    public function updated(Training $training)
    {
        saveStatus($training, 'Edited', 'Mengubah pendaftaran training.');
    }

    /**
     * Handle the training "deleted" event.
     *
     * @param  \App\Training  $training
     * @return void
     */
    public function deleted(Training $training)
    {
        //
    }

    /**
     * Handle the training "restored" event.
     *
     * @param  \App\Training  $training
     * @return void
     */
    public function restored(Training $training)
    {
        //
    }

    /**
     * Handle the training "force deleted" event.
     *
     * @param  \App\Training  $training
     * @return void
     */
    public function forceDeleted(Training $training)
    {
        //
    }
}
