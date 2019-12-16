<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Datakrama\Eloquid\Traits\Uuids;

class Department extends Model
{
    use Uuids;
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the school implementation for the department.
     */
    public function schoolImplementations()
    {
        return $this->hasMany('App\SchoolImplementation');
    }

    /**
     * Get the student class for the department.
     */
    public function studentClasses()
    {
        return $this->hasMany('App\StudentClass');
    }

    /**
     * Get all of the students for the department.
     */
    public function students()
    {
        return $this->hasManyThrough('App\Student', 'App\StudentClass', 'department_id', 'class_id');
    }
}
