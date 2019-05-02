<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolPhoto extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'name',
    ];

    /**
     * Get the school that owns the photo.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }
}
