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
        $this->saveStatus($training, 'Created', 'Mendaftar program training.');
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
        $this->saveStatus($training, 'Edited', 'Mengubah pendaftaran training.');
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
     * Save status
     * 
     * @param  \App\Training  $training
     * @param  string  $status
     * @param  string  $desc
     */
    public function saveStatus($training, $status, $desc)
    {
        $log = actlog($desc);
        $status = Status::byName($status)->first();
        $training->status()->attach($status->id, [
            'log_id' => $log,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Create payment
     * 
     * @param  \App\Training  $training
     */
    public function createPayment($training)
    {
        $training = Training::doesntHave('trainingPayment')->where('id', $training->id)->first();
        if ($training) {
            if ($training->batch != 'Waiting') {
                $payment = Payment::create([
                    'school_id' => $training->school->id,
                    'type' => 'Commitment Fee'
                ]);
                $this->savePaymentStatus($payment, 'Published', 'Menerbitkan konfirmasi pembayaran.');
                $training->payment()->attach($payment->id, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }/**
     * Save payment status
     * 
     * @param  \App\Payment  $payment
     * @param  string  $status
     * @param  string  $desc
     */
    public function savePaymentStatus($payment, $status, $desc)
    {
        $log = actlog($desc);
        $status = Status::byName($status)->first();
        $payment->status()->attach($status->id, [
            'log_id' => $log,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Send notification
     * 
     * @param  \App\Training  $training
     */
    public function sendNotification($training)
    {
        $school = School::findOrFail($training->school->id);
        if ($training->batch == 'Waiting') {
            // $school->notify(new TrainingWaited($training));
        } elseif ($training->batch != 'Waiting') {
            // $school->notify(new TrainingApproved($training));
        }
    }
}
