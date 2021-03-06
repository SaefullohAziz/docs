<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Datakrama\Eloquid\Traits\Uuids;

class SchoolStatus extends Model
{
    use Uuids;
    
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
    public function statusUpdates()
    {
        return $this->hasMany('App\SchoolStatusUpdate');
    }

    /**
     * Get the update for the status.
     */
    public function statusUpdate()
    {
        return $this->hasMany('App\SchoolStatusUpdate')->whereRaw('NOT EXISTS (SELECT 1 FROM school_status_updates t2 WHERE t2.school_id = school_status_updates.school_id AND t2.created_at > school_status_updates.created_at)');
    }

    /**
     * Get the level that owns the status.
     */
    public function level()
    {
        return $this->belongsTo('App\SchoolLevel', 'school_level_id');
    }

    /**
     * The school that belong to the status.
     */
    public function schools()
    {
        return $this->belongsToMany('App\School', 'school_status_updates')->using('App\SchoolStatusUpdate')->as('status_update')->withTimestamps()->whereRaw('NOT EXISTS (SELECT 1 FROM school_status_updates t2 WHERE t2.school_id = school_status_updates.school_id AND t2.created_at > school_status_updates.created_at)');
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
