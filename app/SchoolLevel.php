<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolLevel extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'name'
    ];

    /**
     * Get the status for the level.
     */
    public function status()
    {
        return $this->hasMany('App\SchoolStatus');
    }
}
