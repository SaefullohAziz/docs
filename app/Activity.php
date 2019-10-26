<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\Uuids;

class Activity extends Model
{
    use Uuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'school_id', 'date', 'until_date', 'time', 'participant', 'amount_of_teacher', 'amount_of_acp_student', 'amount_of_reguler_student', 'amount_of_student', 'activity', 'period', 'submission_letter', 'detail'];

    /**
     * Get the school that owns the activity.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    /**
     * Get the activity status for the activity.
     */
    public function activityStatuses()
    {
        return $this->hasMany('App\ActivityStatus');
    }

     /**
     * Get the latest activity status for the activity.
     */
    public function activityStatus()
    {
        return $this->hasOne('App\ActivityStatus')->orderBy('created_at', 'desc')->limit(1);
    }

    /**
     * The status that belong to the activity.
     */
    public function statuses()
    {
        return $this->belongsToMany('App\Status', 'activity_statuses')->using('App\ActivityStatus')->withTimestamps();
    }

    /**
     * Get the activity pic for the activity.
     */
    public function activityPic()
    {
        return $this->hasOne('App\ActivityPic');
    }

    /**
     * The pic that belong to the activity.
     */
    public function pic()
    {
        return $this->belongsToMany('App\Pic', 'activity_pics')->using('App\ActivityPic')->withTimestamps();
    }

    /**
     * Main query for listing
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function get(Request $request)
    {
        return DB::table('activities')
            ->join('schools', 'activities.school_id', '=', 'schools.id')
            ->join('activity_statuses', 'activity_statuses.id', '=', DB::raw('(SELECT id FROM activity_statuses WHERE activity_statuses.activity_id = activities.id ORDER BY created_at DESC LIMIT 1)'))
            ->join('statuses', 'activity_statuses.status_id', '=', 'statuses.id')
            ->join('activity_pics', 'activities.id', '=', 'activity_pics.activity_id')
            ->join('pics', 'activity_pics.pic_id', '=', 'pics.id')
            ->when(auth()->guard('web')->check(), function ($query) use ($request) {
                $query->where('schools.id', auth()->user()->school->id);
            })->when( ! empty($request->school), function ($query) use ($request) {
                $query->where('schools.id', $request->school);
            })->when( ! empty($request->type), function ($query) use ($request) {
                $query->where('activities.type', $request->type);
            })->when( ! empty($request->status), function ($query) use ($request) {
                $query->where('statuses.id', $request->status);
            })
            ->whereNull('schools.deleted_at')
            ->whereNull('activities.deleted_at');
    }

    /**
     * Show the list for datatable
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function list(Request $request)
    {
        return self::get($request)->select('activities.*', 'schools.name as school', 'pics.name as pic_name', 'statuses.name as status');
    }
}
