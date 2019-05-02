<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolStatus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_by', 'name', 'alias',
    ];

    /**
     * Get the update for the status.
     */
    public function statusUpdate()
    {
        return $this->hasMany('App\SchoolStatusUpdate');
    }

    /**
     * Get the level that owns the status.
     */
    public function level()
    {
        return $this->belongsTo('App\SchoolLevel');
    }

    /**
     * The school that belong to the status.
     */
    public function school()
    {
        return $this->belongsToMany('App\School', 'school_status_updates');
    }

    /**
     * Scope a query to only include specific status of given name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetByName($query, $name)
    {
        return $query->where('name', $name)->first();
    }
}
