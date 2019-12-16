<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Datakrama\Eloquid\Traits\Uuids;

class AttendanceStatus extends Pivot
{
    use Uuids;
    
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
