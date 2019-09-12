<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamReadiness extends Model
{
    use SoftDeletes;

    protected $fillable = ['school_id', 'exam_type', 'sub_exam_type', 'ma_status', 'reference_school', 'execution', 'token'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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
    public function examReadinessStusent()
    {
        return $this->hasMany('App\ExamReadinessStudent');
    }

    /**
     * The status that belong to the exam readiness.
     */
    public function student()
    {
        return $this->belongsToMany('App\student', 'exam_readiness_students');
    }

    /**
     * Get the exam readiness status for the exam readiness.
     */
    public function examReadinessStatus()
    {
        return $this->hasMany('App\ExamReadinessStatus');
    }

     /**
     * Get the latest exam readiness status for the exam readiness.
     */
    public function latestExamReadinessStatus()
    {
        return $this->hasOne('App\ExamReadinessStatus')->orderBy('id', 'desc')->limit(1);
    }

    /**
     * The status that belong to the exam readiness.
     */
    public function status()
    {
        return $this->belongsToMany('App\Status', 'exam_readiness_statuses');
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
        return $this->belongsToMany('App\Pic', 'exam_readiness_pics');
    }

    /**
     * Main query for listing
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function _get(Request $request)
    {
        return DB::table('exam_readinesses')
            ->join('exam_readiness_statuses', 'exam_readiness_statuses.id', '=', DB::raw('(SELECT id FROM exam_readiness_statuses WHERE exam_readiness_statuses.exam_readiness_id = exam_readinesses.id ORDER BY id DESC LIMIT 1)'))
            ->join('statuses', 'exam_readiness_statuses.status_id', '=', 'statuses.id')
            ->join('activity_logs', 'exam_readiness_statuses.log_id', '=', 'activity_logs.id')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->leftJoin('staffs', 'activity_logs.staff_id', '=', 'staffs.id')
            ->join('exam_readiness_pics', 'exam_readinesses.id', '=', 'exam_readiness_pics.exam_readiness_id')
            ->join('pics', 'exam_readiness_pics.pic_id', '=', 'pics.id')
            ->join('schools', 'exam_readinesses.school_id', '=', 'schools.id')
            ->leftJoin('provinces', 'schools.province', '=', 'provinces.name')
            ->when(auth()->guard('web')->check(), function ($query) use ($request) {
                $query->where('schools.school_id', auth()->user()->school->id);
            })->when( ! empty($request->type), function ($query) use ($request) {
                $query->where('exam_readinesses.exam_type', $request->type);
            })
            ->whereNull('schools.deleted_at')
            ->whereNull('exam_readinesses.deleted_at');
    }

    /**
     * Show subsidy list for datatable
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function list(Request $request)
    {
        return self::_get($request)->select('exam_readinesses.*', 'schools.name as school', 'statuses.name as status');
}
    }
