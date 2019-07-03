<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get the subsidy status for the status.
     */
    public function subsidyStatus()
    {
        return $this->hasMany('App\SubsidyStatus');
    }

    /**
     * The subsidy that belong to the status.
     */
    public function subsidy()
    {
        return $this->belongsToMany('App\Subsidy', 'subsidy_statuses');
    }

    /**
     * Scope a query to only include specific status of given names.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByNames($query, $names)
    {
        return $query->whereIn('name', $names);
    }

    /**
     * Scope a query to only include specific status of given name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }
}
