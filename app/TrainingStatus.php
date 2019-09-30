<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class TrainingStatus extends Model
{
    use Uuids;
    
    /**
     * Get the training that owns the training status.
     */
    public function training()
    {
        return $this->belongsTo('App\Training');
    }

    /**
     * Get the status that owns the training status.
     */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    /**
     * Get the activity log that owns the training status.
     */
    public function log()
    {
        return $this->belongsTo('App\ActivityLog', 'log_id');
    }
}
