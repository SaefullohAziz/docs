<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Island extends Model
{
    use Uuids;
    
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Get the province for the island.
     */
    public function provinces()
    {
        return $this->hasMany('App\Province');
    }
    
    /**
     * Get all of the schools for the islands.
     */
    public function schools()
    {
        return $this->hasManyThrough('App\School', 'App\Province', 'island_id', 'province', 'id', 'name');
    }
}
