<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

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
    public function schoolImplementation()
    {
        return $this->hasMany('App\SchoolImplementation');
    }

    /**
     * Get the student class for the department.
     */
    public function studentClass()
    {
        return $this->hasMany('App\StudentClass');
    }
}
