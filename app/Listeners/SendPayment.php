<?php

namespace App\Listeners;

use App\Payment;
use App\Status;
use App\Events\PaymentSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPayment
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
     * @param  PaymentSent  $event
     * @return void
     */
    public function handle(PaymentSent $event)
    {
        $event->request->validate([
            'koli' => 'required',
            'awb_number' => 'required',
            'expedition' => 'required',
            'proof_of_receipt' => 'required'
        ]);
        $this->saveStatusBatch('Sent', 'Mengirim subsidi.', $event->request);
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
            $request->merge([
                'proof_of_receipt' => $this->uploadProofOfReceipt($payment, $request),
            ]);
            saveStatus($payment, $status, $desc, [
                'koli' => $request->koli,
                'awb_number' => $request->awb_number,
                'expedition' => $request->expedition,
                'proof_of_receipt' => $request->proof_of_receipt
            ]);
        }
    }

    /**
     * Upload proof of receipt
     * 
     * @param  \App\Payment  $payment
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $oldFile
     * @return string
     */
    public function uploadProofOfReceipt($payment, $request, $oldFile = null)
    {
        if ($request->hasFile('proof_of_receipt')) {
            $filename = 'proof-of-receipt-'.date('d-m-y-h-m-s-').md5(uniqid(rand(), true)).'.'.$request->proof_of_receipt->extension();
            $path = $request->proof_of_receipt->storeAs('public/payment/proof-of-receipt/'.$payment->id, $filename);
            return $payment->id.'/'.$filename;
        }
        return $oldFile;
    }
}
