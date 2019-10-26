<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\Uuids;

class ExamReadiness extends Model
{
    use Uuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'exam_type', 'sub_exam_type', 'ma_status', 'reference_school', 'execution', 'token'
    ];

    /**
     * Get the school that owns the exam readiness.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    /**
     * Get the exam readiness students for the exam readiness.
     */
    public function examReadinessStudents()
    {
        return $this->hasMany('App\ExamReadinessStudent');
    }

    /**
     * The status that belong to the exam readiness.
     */
    public function students()
    {
        return $this->belongsToMany('App\Student', 'exam_readiness_students')->using('App\ExamReadinessStudent')->withTimestamps();
    }

    /**
     * Get the exam readiness status for the exam readiness.
     */
    public function examReadinessStatuses()
    {
        return $this->hasMany('App\ExamReadinessStatus');
    }

     /**
     * Get the latest exam readiness status for the exam readiness.
     */
    public function examReadinessStatus()
    {
        return $this->hasOne('App\ExamReadinessStatus')->orderBy('created_at', 'desc')->limit(1);
    }

    /**
     * The status that belong to the exam readiness.
     */
    public function statuses()
    {
        return $this->belongsToMany('App\Status', 'exam_readiness_statuses')->using('App\ExamReadinessStatus')->withTimestamps();
    }

    /**
     * Get the exam readiness pic for the exam readiness.
     */
    public function examReadinessPic()
    {
        return $this->hasOne('App\ExamReadinessPic');
    }

    /**
     * The pic that belong to the exam readiness.
     */
    public function pic()
    {
        return $this->belongsToMany('App\Pic', 'exam_readiness_pics')->using('App\ExamReadinessPic')->withTimestamps();
    }

    /**
     * Get the sub type.
     *
     * @param  string  $value
     * @return string
     */
    public function getSubTypeAttribute()
    {
        if (strpos($this->sub_exam_type, ', ') !== false) {
            return explode(', ', $this->sub_exam_type);
        }
        return $this->sub_exam_type;
    }

    /**
     * Main query for listing
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function get(Request $request)
    {
        return DB::table('exam_readinesses')
            ->join('exam_readiness_statuses', 'exam_readiness_statuses.id', '=', DB::raw('(SELECT id FROM exam_readiness_statuses WHERE exam_readiness_statuses.exam_readiness_id = exam_readinesses.id ORDER BY created_at DESC LIMIT 1)'))
            ->join('statuses', 'exam_readiness_statuses.status_id', '=', 'statuses.id')
            ->join('activity_logs', 'exam_readiness_statuses.log_id', '=', 'activity_logs.id')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->leftJoin('staffs', 'activity_logs.staff_id', '=', 'staffs.id')
            ->join('exam_readiness_pics', 'exam_readinesses.id', '=', 'exam_readiness_pics.exam_readiness_id')
            ->join('pics', 'exam_readiness_pics.pic_id', '=', 'pics.id')
            ->join('schools', 'exam_readinesses.school_id', '=', 'schools.id')
            ->leftJoin('provinces', 'schools.province', '=', 'provinces.name')
            ->when(auth()->guard('web')->check(), function ($query) use ($request) {
                $query->where('schools.id', auth()->user()->school->id);
            })->when( ! empty($request->school), function ($query) use ($request) {
                $query->where('schools.id', $request->school);
            })->when( ! empty($request->type), function ($query) use ($request) {
                $query->where('exam_readinesses.exam_type', $request->type);
            })->when($request->is('admin/exam/readiness/list')||$request->is('admin/exam/readiness/export'), function ($query) {
                $query->whereNull('exam_readinesses.deleted_at');
            })->when($request->is('admin/exam/readiness/binList'), function ($query) {
                $query->whereNotNull('exam_readinesses.deleted_at');
            })
            ->whereNull('schools.deleted_at');
    }

    /**
     * Show subsidy list for datatable
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function list(Request $request)
    {
        return self::get($request)->select('exam_readinesses.*', 'schools.name as school', 'statuses.name as status');
}
    }
