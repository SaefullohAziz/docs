<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamReadinessStudent extends Model
{
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
