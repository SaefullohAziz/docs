<?php

namespace App\Listeners;

use App\Training;
use App\Payment;
use App\Events\TrainingRegistered;
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
     * @param  TrainingRegistered  $event
     * @return void
     */
    public function handle(TrainingRegistered $event)
    {
        $training = Training::withCount('participants')->doesntHave('trainingPayment')->where('id', $event->training->id)->first();
        $setting = collect(json_decode(setting('training_settings')))->where('name', $training->type)->first();
        $price = setting($setting->default_participant_price_slug);
        if ($training) {
            if ($training->participants_count > 2) {
                $price = $price+(setting($setting->more_participant_slug)*($training->participants_count-2));
            }
            $payment = Payment::create([
                'school_id' => $training->school->id,
                'type' => 'Commitment Fee',
                'total' => $price,
            ]);
            saveStatus($payment, 'Published', 'Menerbitkan konfirmasi pembayaran.');
            $training->payment()->sync([$payment->id]);
        }
    }
}
