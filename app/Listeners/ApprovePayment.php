<?php

namespace App\Listeners;

use App\Payment;
use App\Status;
use App\Events\PaymentApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovePayment
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
     * @param  PaymentApproved  $event
     * @return void
     */
    public function handle(PaymentApproved $event)
    {
        $this->saveStatusBatch('Approved', 'Menyetujui konfirmasi pembayaran.', $event->request);
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
