<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class School extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'name', 'address', 'province', 'regency', 'police_number', 'since', 'school_phone_number', 'school_email', 'school_web', 'total_student', 'department', 'iso_certificate', 'mikrotik_academy', 'headmaster_name', 'headmaster_phone_number', 'headmaster_email', 'reference', 'dealer_name', 'dealer_phone_number', 'dealer_email', 'proposal', 'code'];

    /**
     * Get the status update for the school.
     */
    public function statusUpdate()
    {
        return $this->hasMany('App\SchoolStatusUpdate');
    }

    /**
     * The status that belong to the school.
     */
    public function status()
    {
        return $this->belongsToMany('App\SchoolStatus', 'school_status_updates');
    }

    /**
     * Get the school pic for the school.
     */
    public function schoolPic()
    {
        return $this->hasOne('App\SchoolPic');
    }

    /**
     * The pic that belong to the school.
     */
    public function pic()
    {
        return $this->belongsToMany('App\Pic', 'school_pics');
    }

    /**
     * Get the photo for the school.
     */
    public function photo()
    {
        return $this->hasMany('App\SchoolPhoto');
    }

    /**
     * Get the student for the school.
     */
    public function student()
    {
        return $this->hasMany('App\Student');
    }

    /**
     * Get the comment for the school.
     */
    public function comment()
    {
        return $this->hasMany('App\SchoolComment');
    }

    /**
     * Get the user for the school.
     */
    public function user()
    {
        return $this->hasMany('App\User');
    }

    /**
     * Main query for listing
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function get(Request $request)
    {
        return DB::table('schools')
            ->leftJoin('provinces', 'schools.province', '=', 'provinces.name')
            ->join('school_pics', 'schools.id', '=', 'school_pics.school_id')
            ->join('pics', 'school_pics.pic_id', '=', 'pics.id')
            ->join('school_status_updates', 'school_status_updates.id', '=', DB::raw('(SELECT id FROM school_status_updates WHERE school_status_updates.school_id = schools.id ORDER BY id DESC LIMIT 1)'))
            ->join('school_statuses', 'school_status_updates.school_status_id', '=', 'school_statuses.id')
            ->join('school_levels', 'school_statuses.school_level_id', '=', 'school_levels.id')
            ->leftJoin('staffs AS status_staff', function ($join) {
                $join->on('school_status_updates.staff_id', '=', 'status_staff.id')
                    ->where('school_status_updates.created_by', '=', 'staff');
            })
            ->leftJoin('users AS status_user', function ($join) {
                $join->on('school_status_updates.user_id', '=', 'status_user.id')
                    ->where('school_status_updates.created_by', '=', 'user');
            })->when( ! empty($request->province), function ($query) use ($request) {
                $query->whereIn('schools.province', $request->province);
            })->when( ! empty($request->regency), function ($query) use ($request) {
                $query->whereIn('schools.regency', $request->regency);
            })->when( ! empty($request->level), function ($query) use ($request) {
                $query->whereIn('school_levels.id', $request->level);
            })->when( ! empty($request->status), function ($query) use ($request) {
                $query->where('school_statuses.id', $request->status);
            });
    }

    /**
     * Show school list for datatable
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function list(Request $request)
    {
        return self::get($request)->select('schools.*', 'provinces.abbreviation', 'pics.name AS pic_name', 'pics.position AS pic_position', 'pics.phone_number AS pic_phone_number', 'pics.email AS pic_email', 'school_statuses.id AS school_status_id', 'school_statuses.order_by AS status_order', 'school_statuses.name AS status_name', 'school_statuses.alias AS status_alias', DB::raw('(CASE WHEN status_staff.name IS NULL THEN status_user.name WHEN status_user.name IS NULL THEN status_staff.name ELSE status_staff.name END) AS status_by'), 'school_status_updates.created_at AS status_at', 'school_levels.id AS school_level_id', 'school_levels.name AS level_name');
    }
}
