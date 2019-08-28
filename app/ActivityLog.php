<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['description', 'created_by', 'staff_id', 'user_id', 'school_id'];

    /**
     * Get the staff that owns the activity log.
     */
    public function staff()
    {
        return $this->belongsTo('App\Admin\User');
    }

    /**
     * Get the subsidy status for the activity log.
     */
    public function subsidyStatus()
    {
        return $this->hasOne('App\SubsidyStatus', 'log_id');
    }

    /**
     * Get the training status for the activity log.
     */
    public function trainingStatus()
    {
        return $this->hasOne('App\TrainingStatus', 'log_id');
    }

    /**
     * Get the payment status for the activity log.
     */
    public function paymentStatus()
    {
        return $this->hasOne('App\PaymentStatus', 'log_id');
    }
}
