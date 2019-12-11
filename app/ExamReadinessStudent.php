<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class ExamReadinessStudent extends Pivot
{
    use Uuids;
    
    /**
     * Get the exam readiness that owns the exam readiness student.
     */
    public function examReadiness()
    {
        return $this->belongsTo('App\ExamReadiness');
    }

    /**
     * Get the student that owns the exam readiness student.
     */
    public function student()
    {
        return $this->belongsTo('App\Student');
    }
}
