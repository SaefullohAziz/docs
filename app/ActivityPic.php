<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class ActivityPic extends Model
{
    use Uuids;
    
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
