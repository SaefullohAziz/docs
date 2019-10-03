<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class TrainingPayment extends Pivot
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'training_payments';

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
