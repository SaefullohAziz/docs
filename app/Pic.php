<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Pic extends Model
{
    use Uuids;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'position', 'phone_number', 'email'];

    /**
     * Get the school pic for the pic.
     */
    public function schoolPic()
    {
        return $this->hasOne('App\SchoolPic');
    }

    /**
     * The school that belong to the pic.
     */
    public function school()
    {
        return $this->belongsToMany('App\School', 'school_pics')->using('App\SchoolPic');
    }

    /**
     * Get the document status for the pic.
     */
    public function documentPic()
    {
        return $this->hasMany('App\DocumentPic');
    }

    /**
     * The document that belong to the pic.
     */
    public function document()
    {
        return $this->belongsToMany('App\Document', 'document_pics')->using('App\DocumentPic');
    }

    /**
     * Get the activity status for the pic.
     */
    public function activityPic()
    {
        return $this->hasMany('App\ActivityPic');
    }

    /**
     * The activity that belong to the pic.
     */
    public function activity()
    {
        return $this->belongsToMany('App\Activity', 'activity_pics')->using('App\ActivityPic');
    }

    /**
     * Get the subsidy status for the pic.
     */
    public function subsidyPic()
    {
        return $this->hasMany('App\SubsidyPic');
    }

    /**
     * The subsidy that belong to the pic.
     */
    public function subsidy()
    {
        return $this->belongsToMany('App\Subsidy', 'subsidy_pics')->using('App\SubsidyPic');
    }

    /**
     * Get the training status for the pic.
     */
    public function trainingPic()
    {
        return $this->hasMany('App\TrainingPic');
    }

    /**
     * The training that belong to the pic.
     */
    public function training()
    {
        return $this->belongsToMany('App\Training', 'training_pics')->using('App\TrainingPic');
    }

    /**
     * Get the exam readiness status for the pic.
     */
    public function examReadinessPic()
    {
        return $this->hasMany('App\ExamReadinessPic');
    }

    /**
     * The exam readiness that belong to the pic.
     */
    public function examReadiness()
    {
        return $this->belongsToMany('App\ExamReadiness', 'exam_readiness_pics')->using('App\ExamReadinessPic');
    }

    /**
     * Scope a query to only include specific pic of given school.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySchool($query, $school)
    {
        return $query->whereHas('schoolPic', function ($subQuery) use ($school) {
            $subQuery->where('school_pics.school_id', $school);
        });
    }
}
