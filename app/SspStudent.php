<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class SspStudent extends Pivot
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ssp_students';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        Pivot::creating(function($pivot) {
            $pivot->id = (string) \Illuminate\Support\Str::uuid();
        });
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['subsidy_id', 'student_id'];

    /**
     * Get the subsidy that owns the ssp student.
     */
    public function subsidy()
    {
        return $this->belongsTo('App\Subsidy');
    }

    /**
     * Get the student that owns the ssp student.
     */
    public function student()
    {
        return $this->belongsTo('App\Student');
    }
}
