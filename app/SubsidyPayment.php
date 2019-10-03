<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class SubsidyPayment extends Pivot
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subsidy_payments';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        Pivot::creating(function($pivot) {
            $pivot->id = (string) \Illuminate\Support\Str::uuid();
        });
    }
    
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
