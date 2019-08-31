<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityPhoto extends Model
{
    /**
     * Get the activity that owns the activity pic.
     */
    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }
}
