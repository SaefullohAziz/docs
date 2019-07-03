<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SspStudent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['subsidy_id', 'student_id'];

    /**
     * Get the subsidy that owns the ssp student.
     */
    public function subsidy()
    {
        return $this->belongsTo('App\Subsidy');
    }

    /**
     * Get the student that owns the ssp student.
     */
    public function student()
    {
        return $this->belongsTo('App\Student');
    }
}
