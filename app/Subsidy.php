<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Datakrama\Eloquid\Traits\Uuids;

class Subsidy extends Model
{
    use Uuids, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['school_id', 'type', 'qty', 'submission_letter'];

    // Date instance fields
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Get the school that owns the subsidy.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    /**
     * Get the subsidy student for the subsidy.
     */
    public function sspStudent()
    {
        return $this->hasMany('App\SspStudent');
    }

    /**
     * The student that belong to the subsidy.
     */
    public function students()
    {
        return $this->belongsToMany('App\Student', 'ssp_students')->using('App\SspStudent')->withTimestamps();
    }

    /**
     * Get the subsidy status for the subsidy.
     */
    public function subsidyStatuses()
    {
        return $this->hasMany('App\SubsidyStatus');
    }

    /**
     * Get the latest subsidy status for the subsidy.
     */
    public function subsidyStatus()
    {
        return $this->hasOne('App\SubsidyStatus')->orderBy('created_at', 'desc')->limit(1);
    }

    /**
     * The status that belong to the subsidy.
     */
    public function statuses()
    {
        return $this->belongsToMany('App\Status', 'subsidy_statuses')->using('App\SubsidyStatus')->withTimestamps();
    }

    /**
     * Get the subsidy pic for the subsidy.
     */
    public function subsidyPic()
    {
        return $this->hasOne('App\SubsidyPic');
    }

    /**
     * The pic that belong to the subsidy.
     */
    public function pic()
    {
        return $this->belongsToMany('App\Pic', 'subsidy_pics')->using('App\SubsidyPic')->withTimestamps();
    }

    /**
     * Get the subsidy payment for the subsidy.
     */
    public function subsidyPayment()
    {
        return $this->hasOne('App\SubsidyPayment');
    }

    /**
     * The payment that belong to the subsidy.
     */
    public function payment()
    {
        return $this->belongsToMany('App\Payment', 'subsidy_payments')->using('App\SubsidyPayment')->withTimestamps();
    }

    /**
     * Main query for listing
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function get(Request $request)
    {
        return DB::table('subsidies')
            ->join('subsidy_statuses', 'subsidy_statuses.id', '=', DB::raw('(SELECT id FROM subsidy_statuses WHERE subsidy_statuses.subsidy_id = subsidies.id ORDER BY created_at DESC LIMIT 1)'))
            ->join('statuses', 'subsidy_statuses.status_id', '=', 'statuses.id')
            ->join('activity_logs', 'subsidy_statuses.log_id', '=', 'activity_logs.id')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->leftJoin('staffs', 'activity_logs.staff_id', '=', 'staffs.id')
            ->join('subsidy_pics', 'subsidies.id', '=', 'subsidy_pics.subsidy_id')
            ->join('pics', 'subsidy_pics.pic_id', '=', 'pics.id')
            ->join('schools', 'subsidies.school_id', '=', 'schools.id')
            ->leftJoin('provinces', 'schools.province', '=', 'provinces.name')
            ->when(auth()->guard('web')->check(), function ($query) use ($request) {
                $query->where('schools.id', auth()->user()->school->id);
            })->when( ! empty($request->school), function ($query) use ($request) {
                $query->where('schools.id', $request->school);
            })->when( ! empty($request->type), function ($query) use ($request) {
                $query->where('subsidies.type', $request->type);
            })->when( ! empty($request->status), function ($query) use ($request) {
                $query->where('statuses.id', $request->status);
            })->when($request->is('admin/subsidy/list')||$request->is('admin/subsidy/export'), function ($query) {
                $query->whereNull('subsidies.deleted_at');
            })->when($request->is('admin/subsidy/binList'), function ($query) {
                $query->whereNotNull('subsidies.deleted_at');
            })->whereNull('schools.deleted_at');
    }

    /**
     * Show subsidy list for datatable
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function list(Request $request)
    {
        return self::get($request)->select('subsidies.*', 'schools.name as school', 'schools.school_email', 'provinces.abbreviation', 'pics.id as pic_id', 'pics.name as pic_name', 'pics.position as pic_position', 'pics.email as pic_email', 'pics.phone_number as pic_phone_number', 'statuses.name as status', 'subsidy_statuses.paid_at', 'subsidy_statuses.invoice', 'subsidy_statuses.starting_price', 'subsidy_statuses.paid_installment', 'subsidy_statuses.lack_of_price', 'subsidy_statuses.created_at as statused_at', DB::raw('(CASE WHEN staffs.name IS NULL THEN users.name WHEN users.name IS NULL THEN staffs.name ELSE staffs.name END) AS status_by'), 'subsidy_statuses.created_at AS status_at');
    }
}
