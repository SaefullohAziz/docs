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
        $this->createPayment($training);
        $this->sendNotification($training);
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

    /**
     * Create payment
     * 
     * @param  \App\Training  $training
     */
    public function createPayment($training)
    {
        $training = Training::withCount(['participants'])->doesntHave('trainingPayment')->where('id', $training->id)->first();
        $total = 3000000;
        if ($training) {
            if ($training->trainingStatus->status->name != 'Waiting') {
                if ($training->participants_count > 2) {
                    $total = $total+(1500000*($training->participants_count-2));
                }
                $payment = Payment::create([
                    'school_id' => $training->school->id,
                    'type' => 'Commitment Fee',
                    'total' => $total,
                ]);
                saveStatus($payment, 'Published', 'Menerbitkan konfirmasi pembayaran.');
                $training->payment()->attach($payment->id);
            }
        }
    }

    /**
     * Send notification
     * 
     * @param  \App\Training  $training
     */
    public function sendNotification($training)
    {
        $school = School::findOrFail($training->school->id);
        if ($training->trainingStatus->status->name == 'Waiting') {
            $school->notify(new TrainingWaited($training));
        } elseif ($training->trainingStatus->status->name != 'Waiting') {
            $school->notify(new TrainingApproved($training));
        }
    }
}
