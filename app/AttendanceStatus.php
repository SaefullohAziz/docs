<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class AttendanceStatus extends Pivot
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attendance_statuses';

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
     * Get the attendance that owns the attendance status.
     */
    public function attendance()
    {
        return $this->belongsTo('App\Attendance');
    }

    /**
     * Get the status that owns the attendance status.
     */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    /**
     * Get the activity log that owns the attendance status.
     */
    public function log()
    {
        return $this->belongsTo('App\ActivityLog', 'log_id');
    }

    /**
     * Get the school status update that owns the attendance status.
     */
    public function schoolStatusUpdate()
    {
        return $this->belongsTo('App\SchoolStatusUpdate');
    }
}
