<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Datakrama\Eloquid\Traits\Uuids;

class ExamReadinessSchool extends Model
{
    use Uuids;
    
     /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the exam type that owns the exam readiness school.
     */
    public function examType()
    {
        return $this->belongsTo('App\ExamType');
    }

    /**
     * Get the school that owns the exam readiness school.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }
}
