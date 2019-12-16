<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Datakrama\Eloquid\Traits\Uuids;

class Teacher extends Model
{
    use Uuids;
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the school that owns the teacher.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    /**
     * Get the training participant for the participant.
     */
    public function trainingParticipants()
    {
        return $this->hasMany('App\TrainingParticipant');
    }

    /**
     * The subsidy that belong to the student.
     */
    public function training()
    {
        return $this->belongsToMany('App\Training', 'training_participants', 'teacher_id')->using('TrainingParticipant')->withTimestamps();
    }

    /**
     * Get the audience participant for the participant.
     */
    public function audienceParticipants()
    {
        return $this->hasMany('App\AudienceParticipant');
    }

    /**
     * The audience that belong to the student.
     */
    public function audience()
    {
        return $this->belongsToMany('App\Attendance', 'audience_participants', 'teacher_id', 'attendance_id')->using('AudienceParticipant')->withTimestamps();
    }

    /**
     * Scope a query to only include specific teacher of given school.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySchool($query, $school)
    {
        return $query->where('school_id', $school);
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
        return '/storage/teacher/photo/'.$this->attributes['photo'];
    }

    /**
     * Main query for listing
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function get(Request $request)
    {
        return DB::table('teachers')
        	->join('schools', 'teachers.school_id', '=', 'schools.id')
            ->leftJoin('provinces', 'schools.province', '=', 'provinces.name')
            ->join('school_status_updates', 'school_status_updates.id', '=', DB::raw('(SELECT school_status_updates.id FROM school_status_updates JOIN school_statuses ON school_status_updates.school_status_id = school_statuses.id JOIN school_levels ON school_statuses.school_level_id = school_levels.id WHERE school_status_updates.school_id = schools.id AND school_levels.type = "Level" ORDER BY school_status_updates.created_at DESC LIMIT 1)'))
            ->join('school_statuses', 'school_status_updates.school_status_id', '=', 'school_statuses.id')
            ->join('school_levels', 'school_statuses.school_level_id', '=', 'school_levels.id')
            ->when(auth()->guard('web')->check(), function ($query) use ($request) {
                $query->where('schools.id', auth()->user()->school->id);
            })->when( ! empty($request->level), function ($query) use ($request) {
            	$query->where('school_levels.id', $request->level);
            })->when( ! empty($request->school), function ($query) use ($request) {
            	$query->where('schools.id', $request->school);
            })->whereNull('schools.deleted_at');
    }

    /**
     * Show student list for datatable
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function list(Request $request)
    {
        return self::get($request)->select('teachers.*', 'schools.name as school', 'provinces.abbreviation as province_abbreviation');
    }
}
