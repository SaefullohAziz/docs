<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubsidyPayment extends Model
{
    /**
     * Get the subsidy that owns the subsidy payment.
     */
    public function subsidy()
    {
        return $this->belongsTo('App\Subsidy');
    }

    /**
     * Get the payment that owns the subsidy payment.
     */
    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }
}
