<?php

namespace App\Listeners;

use App\Subsidy;
use App\Payment;
use App\Status;
use App\Events\SubsidyApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateSubsidyPayment
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
     * @param  SubsidyApproved  $event
     * @return void
     */
    public function handle(SubsidyApproved $event)
    {
        foreach ($event->request->selectedData as $id) {
            $subsidy = Subsidy::doesntHave('subsidyPayment')->where('id', $id)->first();
            if ($subsidy) {
                $payment = Payment::create([
                    'school_id' => $subsidy->school->id,
                    'type' => 'Subsidi'
                ]);
                saveStatus($payment, 'Published', 'Menerbitkan konfirmasi pembayaran.', [
                    'created_at' => now()->addSeconds(5),
                    'updated_at' => now()->addSeconds(5)
                ]);
                $subsidy->payment()->attach($payment->id);
            }
        }
    }
}
