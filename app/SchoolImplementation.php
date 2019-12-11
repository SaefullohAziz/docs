<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class SchoolImplementation extends Pivot
{
    use Uuids;
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the school that owns the implementation.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    /**
     * Get the department that owns the implementation.
     */
    public function department()
    {
        return $this->belongsTo('App\Department');
    }
}
