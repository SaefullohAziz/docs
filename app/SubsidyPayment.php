<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class SubsidyPayment extends Model
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
