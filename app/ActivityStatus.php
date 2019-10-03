<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class ActivityStatus extends Pivot
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_statuses';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        Pivot::creating(function($pivot) {
            $pivot->id = (string) \Illuminate\Support\Str::uuid();
        });
    }
    
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
