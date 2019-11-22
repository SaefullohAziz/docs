<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use App\Traits\Uuids;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class School extends Model
{
    use Uuids, HasRelationships, SoftDeletes, Notifiable;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->code = mt_rand(1000000, 9999999);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'name', 'address', 'province', 'regency', 'police_number', 'since', 'school_phone_number', 'school_email', 'school_web', 'total_student', 'department', 'iso_certificate', 'mikrotik_academy', 'headmaster_name', 'headmaster_phone_number', 'headmaster_email', 'reference', 'dealer_name', 'dealer_phone_number', 'dealer_email', 'proposal', 'code'];

    /**
     * Get the province that owns the school.
     */
    public function province()
    {
        return $this->belongsTo('App\Province', 'province', 'name');
    }

    /**
     * Get the status update for the school.
     */
    public function statusUpdates()
    {
        return $this->hasMany('App\SchoolStatusUpdate');
    }

    /**
     * Get the latest status update for the school.
     */
    public function statusUpdate()
    {
        return $this->hasOne('App\SchoolStatusUpdate')->orderBy('created_at', 'desc');
    }

    /**
     * The status that belong to the school.
     */
    public function statuses()
    {
        return $this->belongsToMany('App\SchoolStatus', 'school_status_updates')->using('App\SchoolStatusUpdate')->withTimestamps();
    }

    /**
     * Get the school pic for the school.
     */
    public function schoolPic()
    {
        return $this->hasOne('App\SchoolPic')->orderBy('created_at', 'asc');
    }

    /**
     * Get the school pic for the school.
     */
    public function schoolPics()
    {
        return $this->hasMany('App\SchoolPic');
    }

    /**
     * The pic that belong to the school.
     */
    public function pic()
    {
        return $this->belongsToMany('App\Pic', 'school_pics')->using('App\SchoolPic')->withTimestamps()->withPivot('created_at')->orderBy('school_pics.created_at', 'asc');
    }

    /**
     * Get the implementation for the school.
     */
    public function implementations()
    {
        return $this->hasMany('App\SchoolImplementation');
    }

    /**
     * The implemented departments that belong to the school.
     */
    public function implementedDepartments()
    {
        return $this->belongsToMany('App\Department', 'school_implementations')->using('App\SchoolImplementation')->withTimestamps();
    }

    /**
     * Get the document for the school.
     */
    public function documents()
    {
        return $this->hasMany('App\Document');
    }

    /**
     * Get the photo for the school.
     */
    public function photos()
    {
        return $this->hasMany('App\SchoolPhoto');
    }

    /**
     * Get the comment for the school.
     */
    public function comments()
    {
        return $this->hasMany('App\SchoolComment');
    }

    /**
     * Get the teacher for the school.
     */
    public function teachers()
    {
        return $this->hasMany('App\Teacher');
    }

    /**
     * Get the student class for the school.
     */
    public function studentClasses()
    {
        return $this->hasMany('App\StudentClass');
    }

    /**
     * Get the students for the school.
     */
    public function students()
    {
        return $this->hasManyDeep('App\Student', ['App\StudentClass'], ['school_id', 'class_id'])->withIntermediate('App\StudentClass', ['generation', 'school_year', 'grade']);
    }

    /**
     * Get the activity for the school.
     */
    public function activities()
    {
        return $this->hasMany('App\Activity');
    }

    /**
     * Get the subsidy for the school.
     */
    public function subsidies()
    {
        return $this->hasMany('App\Subsidy');
    }

    /**
     * Get the training for the school.
     */
    public function trainings()
    {
        return $this->hasMany('App\Training');
    }

    /**
     * Get the exam readiness for the school.
     */
    public function examReadinesses()
    {
        return $this->hasMany('App\ExamReadiness');
    }

    /**
     * Get the exam readiness school for the school.
     */
    public function examReadinessSchool()
    {
        return $this->hasOne('App\ExamReadinessSchool');
    }

    /**
     * Get the visitation destination for the school.
     */
    public function visitationDestinations()
    {
        return $this->hasMany('App\VisitationDestination');
    }

    /**
     * Get the attendance for the school.
     */
    public function attendances()
    {
        return $this->hasMany('App\Attendance');
    }

    /**
     * Get the payment for the school.
     */
    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    /**
     * Get the user for the school.
     */
    public function user()
    {
        return $this->hasOne('App\User');
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    /**
     * Main query for listing
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function getList(Request $request)
    {
        return DB::table('schools')
            ->leftJoin('provinces', 'schools.province', '=', 'provinces.name')
            ->leftJoin('islands', 'provinces.island_id', '=', 'islands.id')
            ->join('school_pics', 'school_pics.id', '=', DB::raw('(SELECT id FROM school_pics WHERE school_pics.school_id = schools.id ORDER BY school_pics.created_at ASC LIMIT 1)'))
            ->join('pics', 'school_pics.pic_id', '=', 'pics.id')
            ->join('school_status_updates', 'school_status_updates.id', '=', DB::raw('(SELECT id FROM school_status_updates WHERE school_status_updates.school_id = schools.id ORDER BY created_at DESC LIMIT 1)'))
            ->join('school_statuses', 'school_status_updates.school_status_id', '=', 'school_statuses.id')
            ->join('school_levels', 'school_statuses.school_level_id', '=', 'school_levels.id')
            ->leftJoin('staffs AS status_staff', function ($join) {
                $join->on('school_status_updates.staff_id', '=', 'status_staff.id')
                    ->where('school_status_updates.created_by', '=', 'staff');
            })
            ->leftJoin('users AS status_user', function ($join) {
                $join->on('school_status_updates.user_id', '=', 'status_user.id')
                    ->where('school_status_updates.created_by', '=', 'user');
            })->when( ! empty($request->islands), function ($query) use ($request) {
                $query->whereIn('islands.id', $request->islands);
            })->when( ! empty($request->provinces), function ($query) use ($request) {
                $query->whereIn('schools.province', $request->provinces);
            })->when( ! empty($request->regencies), function ($query) use ($request) {
                $query->whereIn('schools.regency', $request->regencies);
            })->when( ! empty($request->levels), function ($query) use ($request) {
                $query->whereIn('school_levels.id', $request->levels);
            })->when( ! empty($request->statuses), function ($query) use ($request) {
                $query->whereIn('school_statuses.id', $request->statuses);
            })->when($request->is('admin/school/list')||$request->is('admin/school/export'), function ($query) {
                $query->whereNull('schools.deleted_at');
            })->when($request->is('admin/school/binList'), function ($query) {
                $query->whereNotNull('schools.deleted_at');
            });
    }

    /**
     * Show school list for datatable
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function list(Request $request)
    {
        return self::getList($request)->select('schools.*', 'provinces.abbreviation', 'pics.name AS pic_name', 'pics.position AS pic_position', 'pics.phone_number AS pic_phone_number', 'pics.email AS pic_email', 'school_statuses.id AS school_status_id', 'school_statuses.order_by AS status_order', 'school_statuses.name AS status_name', 'school_statuses.alias AS status_alias', DB::raw('(CASE WHEN status_staff.name IS NULL THEN status_user.name WHEN status_user.name IS NULL THEN status_staff.name ELSE status_staff.name END) AS status_by'), 'school_status_updates.created_at AS status_at', 'school_levels.id AS school_level_id', 'school_levels.name AS level_name');
    }

    /**
     * Scope a query to only include specific school of given level.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByLevel($query, $level)
    {
        return $query->whereHas('status', function ($subQuery) use ($level) {
            $subQuery->where('school_statuses.school_level_id', $level);
        });
    }
}
