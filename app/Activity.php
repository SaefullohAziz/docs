<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    /**
     * Get the school that owns the activity.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    /**
     * Get the activity status for the activity.
     */
    public function activityStatus()
    {
        return $this->hasMany('App\ActivityStatus');
    }

     /**
     * Get the latest activity status for the activity.
     */
    public function latestActivityStatus()
    {
        return $this->hasOne('App\ActivityStatus')->orderBy('id', 'desc')->limit(1);
    }

    /**
     * The status that belong to the activity.
     */
    public function status()
    {
        return $this->belongsToMany('App\Status', 'activity_statuses');
    }

    /**
     * Get the activity pic for the activity.
     */
    public function activityPic()
    {
        return $this->hasOne('App\ActivityPic');
    }

    /**
     * The pic that belong to the activity.
     */
    public function pic()
    {
        return $this->belongsToMany('App\Pic', 'activity_pics');
    }
}
