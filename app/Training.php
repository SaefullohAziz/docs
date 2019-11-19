<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\Uuids;
use App\Training;
use App\School;
use Auth;

class Training extends Model
{
    use Uuids, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['school_id', 'type', 'has_asset', 'implementation', 'approval_code', 'selection_result', 'room_type', 'room_size', 'booking_code', 'approval_letter_of_commitment_fee'];

    /**
     * Get the school that owns the subsidy.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    /**
     * Get the training participant for the training.
     */
    public function trainingParticipants()
    {
        return $this->hasMany('App\TrainingParticipant');
    }

    /**
     * The participant that belong to the training.
     */
    public function participants()
    {
        return $this->belongsToMany('App\Teacher', 'training_participants', 'training_id', 'teacher_id')->using('App\TrainingParticipant')->withTimestamps();
    }

    /**
     * Get the training status for the training.
     */
    public function trainingStatuses()
    {
        return $this->hasMany('App\TrainingStatus');
    }

    /**
     * Get the latest training status for the training.
     */
    public function trainingStatus()
    {
        return $this->hasOne('App\TrainingStatus')->orderBy('created_at', 'desc')->limit(1);
    }

    /**
     * The status that belong to the training.
     */
    public function statuses()
    {
        return $this->belongsToMany('App\Status', 'training_statuses')->using('App\TrainingStatus')->withTimestamps();
    }

    /**
     * Get the training pic for the training.
     */
    public function trainingPic()
    {
        return $this->hasOne('App\TrainingPic');
    }

    /**
     * The pic that belong to the training.
     */
    public function pic()
    {
        return $this->belongsToMany('App\Pic', 'training_pics')->using('App\TrainingPic')->withTimestamps();
    }

    /**
     * Get the training payment for the training.
     */
    public function trainingPayment()
    {
        return $this->hasOne('App\TrainingPayment');
    }

    /**
     * The payment that belong to the training.
     */
    public function payment()
    {
        return $this->belongsToMany('App\Payment', 'training_payments')->using('App\TrainingPayment')->withTimestamps();
    }

    /**
     * Main query for listing
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function get(Request $request)
    {
        return DB::table('trainings')
            ->join('training_statuses', 'training_statuses.id', '=', DB::raw('(SELECT id FROM training_statuses WHERE training_statuses.training_id = trainings.id ORDER BY created_at DESC LIMIT 1)'))
            ->join('statuses', 'training_statuses.status_id', '=', 'statuses.id')
            ->join('activity_logs', 'training_statuses.log_id', '=', 'activity_logs.id')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->leftJoin('staffs', 'activity_logs.staff_id', '=', 'staffs.id')
            ->join('training_pics', 'trainings.id', '=', 'training_pics.training_id')
            ->join('pics', 'training_pics.pic_id', '=', 'pics.id')
            ->join('schools', 'trainings.school_id', '=', 'schools.id')
            ->leftJoin('provinces', 'schools.province', '=', 'provinces.name')
            ->when(auth()->guard('web')->check(), function ($query) use ($request) {
                $query->where('schools.id', auth()->user()->school->id);
            })->when( ! empty($request->school), function ($query) use ($request) {
                $query->where('schools.id', $request->school);
            })->when( ! empty($request->type), function ($query) use ($request) {
                $query->where('trainings.type', $request->type);
            })->when( ! empty($request->status), function ($query) use ($request) {
                $query->where('statuses.id', $request->status);
            })->when($request->is('admin/training/list')||$request->is('admin/training/export'), function ($query) {
                $query->whereNull('trainings.deleted_at');
            })->when($request->is('admin/training/binList'), function ($query) {
                $query->whereNotNull('trainings.deleted_at');
            })->whereNull('schools.deleted_at');
    }

    /**
     * Show training list for datatable
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function list(Request $request)
    {
        return self::get($request)->select('trainings.*', 'schools.name as school', 'schools.school_email', 'provinces.abbreviation', 'pics.id as pic_id', 'pics.name as pic_name', 'pics.position as pic_position', 'pics.email as pic_email', 'pics.phone_number as pic_phone_number', 'statuses.name as status', 'training_statuses.created_at as statused_at', DB::raw('(CASE WHEN staffs.name IS NULL THEN users.name WHEN users.name IS NULL THEN staffs.name ELSE staffs.name END) AS status_by'));
    }

    /**
     * Count registerred training by implementation and date
     * 
     * @param  $type
     * @param  $implementation
     * @param  $date
     */
    public static function registerredCount($type = null, $implementation = null, $date)
    {
        return self::where('created_at', '>', $date)
        ->When($implementation, function($query) use ($implementation){
            $query->where('implementation', $implementation);
        })->When($type, function($query) use ($type){
            $query->where('type', $type);
        })->when(Auth::guard('web')->check(), function($query){
            $query->whereIn('implementation', Auth::user()->school->implementedDepartments->pluck('name')->toArray());
        })
        ->count();
    }
}
