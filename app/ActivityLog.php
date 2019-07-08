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
     * Get the subsidy status for the activity log.
     */
    public function subsidyStatus()
    {
        return $this->hasMany('App\SubsidyStatus', 'log_id');
    }

    /**
     * Get the training status for the activity log.
     */
    public function trainingStatus()
    {
        return $this->hasMany('App\TrainingStatus', 'log_id');
    }
}
