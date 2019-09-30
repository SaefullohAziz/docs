<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class SchoolLevel extends Model
{
    use Uuids;
    
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
