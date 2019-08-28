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
                $this->saveStatus($payment, 'Published', 'Menerbitkan konfirmasi pembayaran.');
                $subsidy->payment()->attach($payment->id, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
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
