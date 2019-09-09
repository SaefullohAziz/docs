<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamReadinessStatus extends Model
{
   /**
     * Get the exam readinesses that owns the exam readiness status.
     */
    public function examReadiness()
    {
        return $this->belongsTo('App\ExamReadiness');
    }

    /**
     * Get the status that owns the exam readiness status.
     */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    /**
     * Get the exam readiness log that owns the exam readiness status.
     */
    public function log()
    {
        return $this->belongsTo('App\ActivityLog', 'log_id');
    }
}
