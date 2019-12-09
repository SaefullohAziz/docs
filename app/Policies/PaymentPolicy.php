<?php

namespace App\Policies;

use App\Admin\User as Staff;
use App\User;
use App\Payment;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    /** 
     * Authorizing specific user to perform any action.
     * 
     * @param  \App\User  $user
     * @return mixed
     */
    public function before($user, $ability)
    {
        
    }
    
    /**
     * Determine whether the user can view any payments.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the payment.
     *
     * @param  \App\User  $user
     * @param  \App\Payment  $payment
     * @return mixed
     */
    public function view(User $user, Payment $payment)
    {
        return $user->school_id === $payment->school_id;
    }

    /**
     * Determine whether the user can create payments.
     *
     * @param  \App\Admin\User  $user
     * @return mixed
     */
    public function adminCreate(Staff $user)
    {
        return \Gate::allows('create-payment');
    }

    /**
     * Determine whether the user can create payments.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return \Gate::allows('create-payment');
    }

    /**
     * Determine whether the user can update the payment.
     *
     * @param  \App\User  $user
     * @param  \App\Payment  $payment
     * @return mixed
     */
    public function update(User $user, Payment $payment)
    {
        if ($user->school_id == $payment->school_id) {
            return $payment->type != 'Subsidi' || $payment->type != 'Commitment Fee';
        }
    }

    /**
     * Determine whether the user can confirm the payment.
     *
     * @param  \App\User  $user
     * @param  \App\Payment  $payment
     * @return mixed
     */
    public function confirm(User $user, Payment $payment)
    {
        if ($user->school_id == $payment->school_id) {
            if ($payment->subsidy->count() || $payment->training->count()) {
                return $payment->paymentStatus->status->name == 'Published';
            }
            return false;
        }
        return false;
    }

    /**
     * Determine whether the user can confirm the payment.
     *
     * @param  \App\User  $user
     * @param  \App\Payment  $payment
     * @return mixed
     */
    public function commitmentFeeCheck(User $user, Payment $payment)
    {
        if ($payment->training->count()) {
            if (date('Y-m-d H:i:s', strtotime($payment->created_at . ' +3 hours')) < date('Y-m-d H:i:s')) {
                saveStatus($payment, 'Expired', 'Konfirmasi pembayaran melewati batas waktu.');
                if ($payment->training()->count()) {
                    $training = \App\Training::find($payment->training[0]->id);
                    saveStatus($training, 'Expired', 'Konfirmasi pembayaran melewati batas waktu.');
                }
                return false;
            }
            return true;
        }
        return true;
    }

    /**
     * Determine whether the user can delete the payment.
     *
     * @param  \App\User  $user
     * @param  \App\Payment  $payment
     * @return mixed
     */
    public function delete(User $user, Payment $payment)
    {
        return $user->school_id === $payment->school_id;
    }

    /**
     * Determine whether the user can restore the payment.
     *
     * @param  \App\User  $user
     * @param  \App\Payment  $payment
     * @return mixed
     */
    public function restore(User $user, Payment $payment)
    {
        return $user->school_id === $payment->school_id;
    }

    /**
     * Determine whether the user can permanently delete the payment.
     *
     * @param  \App\User  $user
     * @param  \App\Payment  $payment
     * @return mixed
     */
    public function forceDelete(User $user, Payment $payment)
    {
        return $user->school_id === $payment->school_id;
    }
}
