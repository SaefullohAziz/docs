<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class SchoolStatusUpdate extends Pivot
{
    use Uuids;
    
    /**
     * Get the school that owns the status update.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    /**
     * Get the status that owns the status update.
     */
    public function status()
    {
        return $this->belongsTo('App\SchoolStatus', 'school_status_id', 'id');
    }

    /**
     * Get the staff that owns the status update.
     */
    public function staff()
    {
        return $this->belongsTo('App\Admin\User', 'staff_id');
    }

    /**
     * Get the staff that owns the status update.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Get the attendance status for the school status update.
     */
    public function attendanceStatus()
    {
        return $this->hasOne('App\AttendanceStatus');
    }
}
