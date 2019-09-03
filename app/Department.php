<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
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
}
