<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class StudentClass extends Model
{
    use Uuids;
    
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['school_id', 'department_id', 'generation', 'school_year', 'grade'];

    /**
     * Get the school that owns the student class.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    /**
     * Get the department that owns the student class.
     */
    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    /**
     * Get the student for the student class.
     */
    public function students()
    {
        return $this->hasMany('App\Student', 'class_id');
    }

    /**
     * Main query for listing
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function get(Request $request)
    {
        return DB::table('student_classes')
        	->join('schools', 'student_classes.school_id', '=', 'schools.id')
            ->leftJoin('provinces', 'schools.province', '=', 'provinces.name')
            ->join('school_status_updates', 'school_status_updates.id', '=', DB::raw('(SELECT school_status_updates.id FROM school_status_updates JOIN school_statuses ON school_status_updates.school_status_id = school_statuses.id JOIN school_levels ON school_statuses.school_level_id = school_levels.id WHERE school_status_updates.school_id = schools.id AND school_levels.type = "Level" ORDER BY school_status_updates.created_at DESC LIMIT 1)'))
            ->join('school_statuses', 'school_status_updates.school_status_id', '=', 'school_statuses.id')
            ->join('school_levels', 'school_statuses.school_level_id', '=', 'school_levels.id')
            ->join('departments', 'student_classes.department_id', '=', 'departments.id')
            ->when(auth()->guard('web')->check(), function ($query) use ($request) {
                $query->where('schools.id', auth()->user()->school->id);
            })->when( ! empty($request->level), function ($query) use ($request) {
            	$query->where('school_levels.id', $request->level);
            })->when( ! empty($request->school), function ($query) use ($request) {
            	$query->where('schools.id', $request->school);
            })->when( ! empty($request->schoolYear), function ($query) use ($request) {
            	$query->where('student_classes.school_year', $request->schoolYear);
            })->when( ! empty($request->generation), function ($query) use ($request) {
            	$query->where('student_classes.generation', $request->generation);
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
        return self::get($request)->select('student_classes.*', 'schools.name AS school', 'provinces.abbreviation AS province_abbreviation', 'departments.name AS department');
    }
}
