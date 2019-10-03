<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class ExamReadinessStudent extends Pivot
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exam_readiness_students';

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
