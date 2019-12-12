<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\Uuids;

class Grant extends Model
{
    use Uuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['school_id', 'requirement'];

    /**
     * Get the school that owns the grant.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    /**
     * Get the grant status for the grant.
     */
    public function grantStatuses()
    {
        return $this->hasMany('App\GrantStatus');
    }

    /**
     * Get the latest grant status for the grant.
     */
    public function grantStatus()
    {
        return $this->hasOne('App\GrantStatus')->orderBy('created_at', 'desc')->limit(1);
    }

    /**
     * The status that belong to the grant.
     */
    public function statuses()
    {
        return $this->belongsToMany('App\Status', 'grant_statuses')->using('App\GrantStatus')->withTimestamps();
    }

    /**
     * Get the grant pic for the grant.
     */
    public function grantPic()
    {
        return $this->hasOne('App\GrantPic');
    }

    /**
     * The pic that belong to the grant.
     */
    public function pic()
    {
        return $this->belongsToMany('App\Pic', 'grant_pics')->using('App\GrantPic')->withTimestamps();
    }

    /**
     * Main query for listing
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function get(Request $request)
    {
        return DB::table('grants')
            ->join('grant_statuses', 'grant_statuses.id', '=', DB::raw('(SELECT id FROM grant_statuses WHERE grant_statuses.grant_id = grants.id ORDER BY created_at DESC LIMIT 1)'))
            ->join('statuses', 'grant_statuses.status_id', '=', 'statuses.id')
            ->join('activity_logs', 'grant_statuses.log_id', '=', 'activity_logs.id')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->leftJoin('staffs', 'activity_logs.staff_id', '=', 'staffs.id')
            ->join('grant_pics', 'grants.id', '=', 'grant_pics.grant_id')
            ->join('pics', 'grant_pics.pic_id', '=', 'pics.id')
            ->join('schools', 'grants.school_id', '=', 'schools.id')
            ->leftJoin('provinces', 'schools.province', '=', 'provinces.name')
            ->join('school_status_updates', 'school_status_updates.id', '=', DB::raw('(SELECT id FROM school_status_updates WHERE school_status_updates.school_id = schools.id ORDER BY created_at DESC LIMIT 1)'))
            ->join('school_statuses', 'school_status_updates.school_status_id', '=', 'school_statuses.id')
            ->join('school_levels', 'school_statuses.school_level_id', '=', 'school_levels.id')
            ->when(auth()->guard('web')->check(), function ($query) {
                $query->where('schools.id', auth()->user()->school->id);
            })->when( ! empty($request->school), function ($query) use ($request) {
                $query->where('schools.id', $request->school);
            })->when( ! empty($request->status), function ($query) use ($request) {
                $query->where('statuses.id', $request->status);
            })->when($request->is('admin/grant/list')||$request->is('admin/grant/export'), function ($query) {
                $query->whereNull('grants.deleted_at');
            })->when($request->is('admin/grant/binList'), function ($query) {
                $query->whereNotNull('grants.deleted_at');
            })
            ->whereNull('schools.deleted_at');
    }

    /**
     * Show main list for datatable
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function list(Request $request)
    {
        return self::get($request)->select('grants.*', DB::raw('(CASE WHEN grants.requirement = 1 THEN "No" WHEN grants.requirement = 2 THEN "Yes" ELSE "N/A" END) AS requirement_as'), 'schools.name as school', 'provinces.abbreviation', 'schools.address', 'school_levels.name as level', 'statuses.name as status', 'grant_statuses.created_at as statused_at', DB::raw('(CASE WHEN staffs.name IS NULL THEN users.name WHEN users.name IS NULL THEN staffs.name ELSE staffs.name END) AS status_by'), 'pics.id as pic_id', 'pics.name as pic_name', 'pics.position as pic_position', 'pics.email as pic_email', 'pics.phone_number as pic_phone_number');
    }
}
