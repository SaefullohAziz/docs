<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class PaymentInstallment extends Model
{
    use Uuids;
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the payment that owns the payment installment.
     */
    public function payment()
    {
        return $this->belongsTo('App\Payment');
    }
}
