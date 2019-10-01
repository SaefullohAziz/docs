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
    public function documentStatus()
    {
        return $this->hasMany('App\DocumentStatus');
    }

    /**
     * The document that belong to the status.
     */
    public function document()
    {
        return $this->belongsToMany('App\Document', 'document_statuses')->using('App\DocumentStatus');
    }

    /**
     * Get the activity status for the status.
     */
    public function activityStatus()
    {
        return $this->hasMany('App\ActivityStatus');
    }

    /**
     * The activity that belong to the status.
     */
    public function activity()
    {
        return $this->belongsToMany('App\Activity', 'activity_statuses')->using('App\ActivityStatus');
    }

    /**
     * Get the subsidy status for the status.
     */
    public function subsidyStatus()
    {
        return $this->hasMany('App\SubsidyStatus');
    }

    /**
     * The subsidy that belong to the status.
     */
    public function subsidy()
    {
        return $this->belongsToMany('App\Subsidy', 'subsidy_statuses')->using('App\SubsidyStatus');
    }

    /**
     * Get the training status for the status.
     */
    public function trainingStatus()
    {
        return $this->hasMany('App\TrainingStatus');
    }

    /**
     * The training that belong to the status.
     */
    public function training()
    {
        return $this->belongsToMany('App\Training', 'training_statuses')->using('App\TrainingStatus');
    }

    /**
     * Get the exam readiness status for the status.
     */
    public function ExamReadinessStatus()
    {
        return $this->hasMany('App\ExamReadiness');
    }

    /**
     * The exam readiness that belong to the status.
     */
    public function examReadiness()
    {
        return $this->belongsToMany('App\ExamReadiness', 'exam_readiness_statuses')->using('App\ExamReadinessStatus');
    }

    /**
     * Get the payment status for the status.
     */
    public function paymentStatus()
    {
        return $this->hasMany('App\PaymentStatus');
    }

    /**
     * The payment that belong to the status.
     */
    public function payment()
    {
        return $this->belongsToMany('App\Payment', 'payment_statuses')->using('App\PaymentStatus');
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
