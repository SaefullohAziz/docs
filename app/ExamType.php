<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    /**
     * Get the exam readiness school for the exam type.
     */
    public function examReadinessSchool()
    {
        return $this->hasMany('App\ExamReadinessSchool');
    }
}
