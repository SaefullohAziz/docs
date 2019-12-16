<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Datakrama\Eloquid\Traits\Uuids;

class ActivityStatus extends Pivot
{
    use Uuids;
    
    /**
     * Get the activity that owns the activity status.
     */
    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }

    /**
     * Get the status that owns the activity status.
     */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    /**
     * Get the activity log that owns the activity status.
     */
    public function log()
    {
        return $this->belongsTo('App\ActivityLog', 'log_id');
    }
}
