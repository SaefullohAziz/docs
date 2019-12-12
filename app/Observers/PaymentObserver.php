<?php

namespace App\Observers;

use App\Payment;

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
        saveStatus($payment, 'Created', 'Membuat konfirmasi pembayaran.');
    }

    /**
     * Handle the payment "updated" event.
     *
     * @param  \App\Payment  $payment
     * @return void
     */
    public function updated(Payment $payment)
    {
        saveStatus($payment, 'Edited', 'Mengubah konfirmasi pembayaran.');
        if ($payment->subsidy()->count()) {
            if ($payment->repayment == 'Paid in installment') {
                if ($payment->total > $payment->installment()->sum('total')) {
                    saveStatus($payment, 'Published', 'Menerbitkan konfirmasi pembayaran karena belum lunas.');
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
}
