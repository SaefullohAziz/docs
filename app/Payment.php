<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\Uuids;

class Payment extends Model
{
    use Uuids, SoftDeletes;
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['notif'];

    /**
     * Get the school that owns the payment.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

     /**
     * Get the installment for the payment.
     */
    public function installments()
    {
        return $this->hasMany('App\PaymentInstallment');
    }

    /**
     * Get the payment status for the payment.
     */
    public function paymentStatuses()
    {
        return $this->hasMany('App\PaymentStatus');
    }

    /**
     * Get the latest payment status for the payment.
     */
    public function paymentStatus()
    {
        return $this->hasOne('App\PaymentStatus')->orderBy('created_at', 'desc')->limit(1);
    }

    /**
     * The status that belong to the payment.
     */
    public function statuses()
    {
        return $this->belongsToMany('App\Status', 'payment_statuses')->using('App\PaymentStatus')->withTimestamps();
    }

    /**
     * Get the subsidy payment for the payment.
     */
    public function subsidyPayment()
    {
        return $this->hasOne('App\SubsidyPayment');
    }

    /**
     * The subsidy that belong to the payment.
     */
    public function subsidy()
    {
        return $this->belongsToMany('App\Subsidy', 'subsidy_payments')->using('App\SubsidyPayment')->withTimestamps();
    }

    /**
     * Get the training payment for the payment.
     */
    public function trainingPayment()
    {
        return $this->hasOne('App\TrainingPayment');
    }

    /**
     * The training that belong to the payment.
     */
    public function training()
    {
        return $this->belongsToMany('App\Training', 'training_payments')->using('App\TrainingPayment')->withTimestamps();
    }

    /**
     * Main query for listing
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function get(Request $request)
    {
        return DB::table('payments')
            ->join('payment_statuses', 'payment_statuses.id', '=', DB::raw('(SELECT id FROM payment_statuses WHERE payment_statuses.payment_id = payments.id ORDER BY created_at DESC LIMIT 1)'))
            ->join('statuses', 'payment_statuses.status_id', '=', 'statuses.id')
            ->join('activity_logs', 'payment_statuses.log_id', '=', 'activity_logs.id')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->leftJoin('staffs', 'activity_logs.staff_id', '=', 'staffs.id')
            ->join('schools', 'payments.school_id', '=', 'schools.id')
            ->leftJoin('provinces', 'schools.province', '=', 'provinces.name')
            ->leftJoin('subsidy_payments', 'payments.id', '=', 'subsidy_payments.payment_id')
            ->leftJoin('subsidies', 'subsidy_payments.subsidy_id', '=', 'subsidies.id')
            ->leftJoin('training_payments', 'payments.id', '=', 'training_payments.payment_id')
            ->leftJoin('trainings', 'training_payments.training_id', '=', 'trainings.id')
            ->when(auth()->guard('web')->check(), function ($query) use ($request) {
                $query->where('schools.id', auth()->user()->school->id);
                $query->where('statuses.name', '!=', 'Published');
            })->when( ! empty($request->school), function ($query) use ($request) {
                $query->where('schools.id', $request->school);
            })->when( ! empty($request->type), function ($query) use ($request) {
                $query->where('payments.type', $request->type);
            })->when( ! empty($request->status), function ($query) use ($request) {
                $query->where('statuses.id', $request->status);
            })->when($request->is('admin/payment/list')||$request->is('admin/payment/export'), function ($query) {
                $query->whereNull('payments.deleted_at');
            })->when($request->is('admin/payment/binList'), function ($query) {
                $query->whereNotNull('payments.deleted_at');
            })
            ->whereNull('schools.deleted_at')
            ->where('statuses.name', '!=', 'Published');
    }

    /**
     * Show training list for datatable
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function list(Request $request)
    {
        return self::get($request)->select('payments.*', 'schools.name as school', 'schools.school_email', 'provinces.abbreviation', 'subsidies.type as subsidy_type', 'trainings.type as training_type', 'statuses.name as status', 'payment_statuses.created_at as statused_at', DB::raw('(CASE WHEN staffs.name IS NULL THEN users.name WHEN users.name IS NULL THEN staffs.name ELSE staffs.name END) AS status_by'));
    }
}
