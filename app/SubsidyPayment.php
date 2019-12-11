<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class SubsidyPayment extends Pivot
{
    use Uuids;
    
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
