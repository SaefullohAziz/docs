<?php

namespace App\Listeners;

use App\Training;
use App\Payment;
use App\Status;
use App\Events\TrainingApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateTrainingPayment
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
     * @param  TrainingApproved  $event
     * @return void
     */
    public function handle(TrainingApproved $event)
    {
        foreach ($event->request->selectedData as $id) {
            $training = Training::doesntHave('trainingPayment')->where('id', $id)->first();
            if ($training) {
                if ($training->batch == 'Waiting') {
                    $payment = Payment::create([
                        'school_id' => $training->school->id,
                        'type' => 'Commitment Fee'
                    ]);
                    $this->saveStatus($payment, 'Published', 'Menerbitkan konfirmasi pembayaran.');
                    $training->payment()->attach($payment->id, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Save status
     * 
     * @param  \App\Payment  $payment
     * @param  string  $status
     * @param  string  $desc
     */
    public function saveStatus($payment, $status, $desc)
    {
        $log = actlog($desc);
        $status = Status::byName($status)->first();
        $payment->status()->attach($status->id, [
            'log_id' => $log,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
