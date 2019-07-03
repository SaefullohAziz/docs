<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pic extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'position', 'phone_number', 'email'];

    /**
     * Get the school pic for the pic.
     */
    public function schoolPic()
    {
        return $this->hasOne('App\SchoolPic');
    }

    /**
     * The school that belong to the pic.
     */
    public function school()
    {
        return $this->belongsToMany('App\School', 'school_pics');
    }

    /**
     * Get the subsidy status for the subsidy.
     */
    public function subsidyPic()
    {
        return $this->hasMany('App\SubsidyPic');
    }

    /**
     * The subsidy that belong to the pic.
     */
    public function subsidy()
    {
        return $this->belongsToMany('App\Subsidy', 'subsidy_pics');
    }

    /**
     * Scope a query to only include specific pic of given school.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySchool($query, $school)
    {
        return $query->whereHas('schoolPic', function ($subQuery) use ($school) {
            $subQuery->where('school_pics.school_id', $school);
        });
    }
}
