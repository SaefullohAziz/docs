<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class ActivityPic extends Pivot
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
