<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Island extends Model
{
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
    public function province()
    {
        return $this->hasMany('App\Province');
    }
}
