<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingPayment extends Model
{
    /**
     * Get the training that owns the training payment.
     */
    public function training()
    {
        return $this->belongsTo('App\Training');
    }

    /**
     * Get the payment that owns the training payment.
     */
    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }
}
