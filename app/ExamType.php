<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Datakrama\Eloquid\Traits\Uuids;

class ExamType extends Model
{
    use Uuids;
    
    /**
     * Get the exam readiness school for the exam type.
     */
    public function examReadinessSchools()
    {
        return $this->hasMany('App\ExamReadinessSchool');
    }
}
