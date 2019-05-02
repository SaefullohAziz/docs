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
}
