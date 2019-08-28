<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Training extends Model
{
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
    public function trainingParticipant()
    {
        return $this->hasMany('App\TrainingParticipant');
    }

    /**
     * The participant that belong to the training.
     */
    public function participant()
    {
        return $this->belongsToMany('App\Teacher', 'training_participants', 'training_id', 'teacher_id');
    }

    /**
     * Get the training status for the training.
     */
    public function trainingStatus()
    {
        return $this->hasMany('App\TrainingStatus');
    }

    /**
     * Get the latest training status for the training.
     */
    public function latestTrainingStatus()
    {
        return $this->hasOne('App\TrainingStatus')->orderBy('id', 'desc')->limit(1);
    }

    /**
     * The status that belong to the training.
     */
    public function status()
    {
        return $this->belongsToMany('App\Status', 'training_statuses');
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
        return $this->belongsToMany('App\Pic', 'training_pics');
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
        return $this->belongsToMany('App\Payment', 'training_payments');
    }

    /**
     * Main query for listing
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function get(Request $request)
    {
        return DB::table('trainings')
            ->join('training_statuses', 'training_statuses.id', '=', DB::raw('(SELECT id FROM training_statuses WHERE training_statuses.training_id = trainings.id ORDER BY id DESC LIMIT 1)'))
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
}
