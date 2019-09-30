<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Status extends Model
{
    use Uuids;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get the subsidy status for the status.
     */
    public function subsidyStatus()
    {
        return $this->hasMany('App\SubsidyStatus');
    }

    /**
     * The subsidy that belong to the status.
     */
    public function subsidy()
    {
        return $this->belongsToMany('App\Subsidy', 'subsidy_statuses');
    }

    /**
     * Get the training status for the status.
     */
    public function trainingStatus()
    {
        return $this->hasMany('App\TrainingStatus');
    }

    /**
     * The training that belong to the status.
     */
    public function training()
    {
        return $this->belongsToMany('App\Training', 'training_statuses');
    }

    /**
     * Get the payment status for the status.
     */
    public function paymentStatus()
    {
        return $this->hasMany('App\PaymentStatus');
    }

    /**
     * The payment that belong to the status.
     */
    public function payment()
    {
        return $this->belongsToMany('App\Payment', 'payment_statuses');
    }

    /**
     * Scope a query to only include specific status of given names.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByNames($query, $names)
    {
        return $query->whereIn('name', $names);
    }

    /**
     * Scope a query to only include specific status of given name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }
}
