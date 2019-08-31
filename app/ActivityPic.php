<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityPic extends Model
{
    /**
     * Get the activity that owns the activity pic.
     */
    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }

    /**
     * Get the pic that owns the activity pic.
     */
    public function pic()
    {
        return $this->belongsTo('App\Pic');
    }
}
