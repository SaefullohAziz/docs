<?php

namespace App\Listeners;

use App\Payment;
use App\Status;
use App\Events\PaymentProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessPayment
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
     * @param  PaymentProcessed  $event
     * @return void
     */
    public function handle(PaymentProcessed $event)
    {
        $this->saveStatusBatch('Processed', 'Memproses konfirmasi pembayaran.', $event->request);
    }

    /**
     * Save status batch
     * 
     * @param  string  $status
     * @param  string  $desc
     * @param  \Illuminate\Http\Request  $request
     */
    public function saveStatusBatch($status, $desc, $request)
    {
        foreach ($request->selectedData as $id) {
            $payment = Payment::find($id);
            saveStatus($payment, $status, $desc);
        }
    }
}
