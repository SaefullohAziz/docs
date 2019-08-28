<?php

namespace App\Observers;

use App\Payment;
use App\Status;

class PaymentObserver
{
    /**
     * Handle the payment "created" event.
     *
     * @param  \App\Payment  $payment
     * @return void
     */
    public function created(Payment $payment)
    {
        $this->saveStatus($payment, 'Created', 'Membuat konfirmasi pembayaran.');
    }

    /**
     * Handle the payment "updated" event.
     *
     * @param  \App\Payment  $payment
     * @return void
     */
    public function updated(Payment $payment)
    {
        $this->saveStatus($payment, 'Edited', 'Mengubah konfirmasi pembayaran.');
        if ($payment->subsidy()->count() > 0) {
            if ($payment->repayment == 'Paid in installment') {
                if ($payment->total > $payment->installment()->sum('total')) {
                    $this->saveStatus($payment, 'Published', 'Menerbitkan konfirmasi pembayaran karena belum lunas.');
                }
            }
        }
    }

    /**
     * Handle the payment "deleted" event.
     *
     * @param  \App\Payment  $payment
     * @return void
     */
    public function deleted(Payment $payment)
    {
        //
    }

    /**
     * Handle the payment "restored" event.
     *
     * @param  \App\Payment  $payment
     * @return void
     */
    public function restored(Payment $payment)
    {
        //
    }

    /**
     * Handle the payment "force deleted" event.
     *
     * @param  \App\Payment  $payment
     * @return void
     */
    public function forceDeleted(Payment $payment)
    {
        //
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
