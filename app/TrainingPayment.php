<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Datakrama\Eloquid\Traits\Uuids;

class TrainingPayment extends Pivot
{
    use Uuids;
    
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
