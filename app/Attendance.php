<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Datakrama\Eloquid\Traits\Uuids;

class Attendance extends Model
{
    use Uuids, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    /**
     * Get the school that owns the attendance.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    /**
     * Get the attendance status for the attendance.
     */
    public function attendanceStatuses()
    {
        return $this->hasMany('App\AttendanceStatus');
    }

     /**
     * Get the latest attendance status for the attendance.
     */
    public function attendanceStatus()
    {
        return $this->hasOne('App\AttendanceStatus')->orderBy('created_at', 'desc')->limit(1);
    }

    /**
     * The status that belong to the attendance.
     */
    public function statuses()
    {
        return $this->belongsToMany('App\Status', 'attendance_statuses')->using('App\AttendanceStatus')->withTimestamps();
    }

    /**
     * Get the audience participant for the attendance.
     */
    public function audienceParticipants()
    {
        return $this->hasMany('App\AudienceParticipant');
    }

    /**
     * The participants that belong to the attendance.
     */
    public function participants()
    {
        return $this->belongsToMany('App\Teacher', 'audience_participants', 'attendance_id', 'teacher_id')->using('App\AudienceParticipant')->withTimestamps();
    }

    /**
     * Main query for listing
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function get(Request $request)
    {
        return DB::table('attendances')
            ->join('schools', 'attendances.school_id', '=', 'schools.id')
            ->leftJoin('provinces', 'schools.province', '=', 'provinces.name')
            ->join('attendance_statuses', 'attendance_statuses.id', '=', DB::raw('(SELECT id FROM attendance_statuses WHERE attendance_statuses.attendance_id = attendances.id ORDER BY created_at DESC LIMIT 1)'))
            ->join('statuses', 'attendance_statuses.status_id', '=', 'statuses.id')
            ->join('activity_logs', 'attendance_statuses.log_id', '=', 'activity_logs.id')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->leftJoin('staffs', 'activity_logs.staff_id', '=', 'staffs.id')
            ->when(auth()->guard('web')->check(), function ($query) use ($request) {
                $query->where('schools.id', auth()->user()->school->id);
            })->when( ! empty($request->school), function ($query) use ($request) {
                $query->where('schools.id', $request->school);
            })->when( ! empty($request->type), function ($query) use ($request) {
                $query->where('activities.type', $request->type);
            })->when( ! empty($request->status), function ($query) use ($request) {
                $query->where('statuses.id', $request->status);
            })->when($request->is('admin/attendance/list')||$request->is('admin/attendance/export'), function ($query) {
                $query->whereNull('attendances.deleted_at');
            })->when($request->is('admin/attendance/binList'), function ($query) {
                $query->whereNotNull('attendances.deleted_at');
            })
            ->whereNull('schools.deleted_at');
    }

    /**
     * Show the list for datatable
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function list(Request $request)
    {
        return self::get($request)->select('attendances.*', 'schools.name as school', 'provinces.abbreviation', 'statuses.name as status', 'attendance_statuses.created_at as statused_at', DB::raw('(CASE WHEN staffs.name IS NULL THEN users.name WHEN users.name IS NULL THEN staffs.name ELSE staffs.name END) AS status_by'));
    }
}
