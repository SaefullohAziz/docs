<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Student extends Model
{
    use Uuids, HasRelationships;
    
	/**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['approval', 'notif'];

    /**
     * Get the school that owns the student.
     */
    public function school()
    {
        return $this->hasManyDeep('App\School', ['App\StudentClass'], ['id', 'id'], ['class_id', 'school_id'])->latest('schools.created_at');
    }

    /**
     * Get the department that owns the student.
     */
    public function department()
    {
        return $this->hasManyDeep('App\Department', ['App\StudentClass'], ['id', 'id'], ['class_id', 'department_id'])->latest('departments.created_at');
    }

    /**
     * Get the class that owns the student.
     */
    public function class()
    {
        return $this->belongsTo('App\StudentClass', 'class_id');
    }

    /**
     * Get the subsidy student for the student.
     */
    public function sspStudent()
    {
        return $this->hasOne('App\SspStudent');
    }

    /**
     * The subsidy that belong to the student.
     */
    public function subsidy()
    {
        return $this->belongsToMany('App\Subsidy', 'ssp_students')->using('App\SspStudent');
    }

    /**
     * Get the exam readiness student for the student.
     */
    public function examReadinessStudent()
    {
        return $this->hasMany('App\examReadinessStudent');
    }

    /**
     * The exam readiness that belong to the student.
     */
    public function examReadiness()
    {
        return $this->belongsToMany('App\ExamReadiness', 'exam_readiness_students')->using('App\examReadinessStudent');
    }

    /**
     * Get the avatar.
     *
     * @param  string  $value
     * @return string
     */
    public function getAvatarAttribute()
    {
        if ($this->attributes['photo'] == 'default.png') {
            return '/img/avatar/default.png';
        }
        return '/storage/student/photo/'.$this->attributes['photo'];
    }

    /**
     * Main query for listing
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function getList(Request $request)
    {
        return DB::table('students')
            ->join('student_classes', 'students.class_id', '=', 'student_classes.id')
            ->join('departments', 'student_classes.department_id', '=', 'departments.id')
        	->join('schools', 'student_classes.school_id', '=', 'schools.id')
            ->leftJoin('provinces', 'schools.province', '=', 'provinces.name')
            ->join('school_status_updates', 'school_status_updates.id', '=', DB::raw('(SELECT school_status_updates.id FROM school_status_updates JOIN school_statuses ON school_status_updates.school_status_id = school_statuses.id JOIN school_levels ON school_statuses.school_level_id = school_levels.id WHERE school_status_updates.school_id = schools.id AND school_levels.type = "Level" ORDER BY school_status_updates.created_at DESC LIMIT 1)'))
            ->join('school_statuses', 'school_status_updates.school_status_id', '=', 'school_statuses.id')
            ->join('school_levels', 'school_statuses.school_level_id', '=', 'school_levels.id')
            ->when(auth()->guard('web')->check(), function ($query) use ($request) {
                $query->where('schools.id', auth()->user()->school->id);
            })->when( ! empty($request->levels), function ($query) use ($request) {
            	$query->whereIn('school_levels.id', $request->levels);
            })->when( ! empty($request->level), function ($query) use ($request) {
            	$query->where('school_levels.id', $request->level);
            })->when( ! empty($request->school), function ($query) use ($request) {
            	$query->where('schools.id', $request->school);
            })->when( ! empty($request->class), function ($query) use ($request) {
            	$query->where('student_classes.id', $request->class);
            })->when( ! empty($request->generation), function ($query) use ($request) {
            	$query->where('student_classes.generation', $request->generation);
            })->when( ! empty($request->schoolYear), function ($query) use ($request) {
            	$query->where('student_classes.school_year', $request->schoolYear);
            })->when( ! empty($request->departments), function ($query) use ($request) {
            	$query->whereIn('departments.id', $request->departments);
            })->when( ! empty($request->department), function ($query) use ($request) {
            	$query->where('departments.id', $request->department);
            })->whereNull('schools.deleted_at');
    }

    /**
     * Show student list for datatable
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function list(Request $request)
    {
        return self::getList($request)->select('students.*', 'schools.name as school', 'provinces.abbreviation as province_abbreviation', 'student_classes.generation', 'student_classes.school_year', 'student_classes.grade', 'departments.name as department', 'school_levels.name as school_level');
    }
}
