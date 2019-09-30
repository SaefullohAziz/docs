<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class ExamType extends Model
{
    use Uuids;
    
    /**
     * Get the exam readiness school for the exam type.
     */
    public function examReadinessSchool()
    {
        return $this->hasMany('App\ExamReadinessSchool');
    }
}
