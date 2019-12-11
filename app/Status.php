<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Status extends Model
{
    use Uuids;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get the document status for the status.
     */
    public function documentStatuses()
    {
        return $this->hasMany('App\DocumentStatus');
    }

    /**
     * The document that belong to the status.
     */
    public function document()
    {
        return $this->belongsToMany('App\Document', 'document_statuses')->using('App\DocumentStatus')->withTimestamps();
    }

    /**
     * Get the activity status for the status.
     */
    public function activityStatuses()
    {
        return $this->hasMany('App\ActivityStatus');
    }

    /**
     * The activity that belong to the status.
     */
    public function activity()
    {
        return $this->belongsToMany('App\Activity', 'activity_statuses')->using('App\ActivityStatus')->withTimestamps();
    }

    /**
     * Get the subsidy status for the status.
     */
    public function subsidyStatuses()
    {
        return $this->hasMany('App\SubsidyStatus');
    }

    /**
     * The subsidy that belong to the status.
     */
    public function subsidy()
    {
        return $this->belongsToMany('App\Subsidy', 'subsidy_statuses')->using('App\SubsidyStatus')->withTimestamps();
    }

    /**
     * Get the training status for the status.
     */
    public function trainingStatuses()
    {
        return $this->hasMany('App\TrainingStatus');
    }

    /**
     * The training that belong to the status.
     */
    public function training()
    {
        return $this->belongsToMany('App\Training', 'training_statuses')->using('App\TrainingStatus')->withTimestamps();
    }

    /**
     * Get the exam readiness status for the status.
     */
    public function examReadinessStatuses()
    {
        return $this->hasMany('App\ExamReadiness');
    }

    /**
     * The exam readiness that belong to the status.
     */
    public function examReadiness()
    {
        return $this->belongsToMany('App\ExamReadiness', 'exam_readiness_statuses')->using('App\ExamReadinessStatus')->withTimestamps();
    }

    /**
     * Get the attendance status for the status.
     */
    public function attendanceStatuses()
    {
        return $this->hasMany('App\AttendanceStatus');
    }

    /**
     * The attendance that belong to the status.
     */
    public function attendance()
    {
        return $this->belongsToMany('App\Attendance', 'attendance_statuses')->using('App\AttendanceStatus')->withTimestamps();
    }

    /**
     * Get the payment status for the status.
     */
    public function paymentStatuses()
    {
        return $this->hasMany('App\PaymentStatus');
    }

    /**
     * The payment that belong to the status.
     */
    public function payment()
    {
        return $this->belongsToMany('App\Payment', 'payment_statuses')->using('App\PaymentStatus')->withTimestamps();
    }

    /**
     * Get the grant status for the status.
     */
    public function grantStatuses()
    {
        return $this->hasMany('App\GrantStatus');
    }

    /**
     * The grant that belong to the status.
     */
    public function grants()
    {
        return $this->belongsToMany('App\Grant', 'grant_statuses')->using('App\GrantStatus')->withTimestamps();
    }

    /**
     * Scope a query to only include specific status of given names.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByNames($query, $names)
    {
        return $query->whereIn('name', $names);
    }

    /**
     * Scope a query to only include specific status of given name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }
}
