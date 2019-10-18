<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class SchoolLevel extends Model
{
    use Uuids, HasRelationships;
    
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
    public function statuses()
    {
        return $this->hasMany('App\SchoolStatus');
    }

    /**
     * Get all of the status update for the school level.
     */
    public function statusUpdate()
    {
        $statuses = \App\School::with(['statusUpdate'])->get()->map(function ($school) {
            return $school->statusUpdate->id;
        })->toArray();
        return $this->hasManyThrough('App\SchoolStatusUpdate', 'App\SchoolStatus')->whereIn('school_status_updates.id', $statuses);
    }

    /**
     * Get all of the status updates for the school level.
     */
    public function statusUpdates()
    {
        return $this->hasManyThrough('App\SchoolStatusUpdate', 'App\SchoolStatus');
    }

    /**
     * Get all of the school for the school level.
     */
    public function schools()
    {
        $statuses = \App\School::with(['statusUpdate'])->get()->map(function ($school) {
            return $school->statusUpdate->id;
        })->toArray();
        return $this->hasManyDeepFromRelations($this->statusUpdate(), (new SchoolStatusUpdate)->school())->whereIn('school_status_updates.id', $statuses);
    }

    /**
     * Get all of the school comments for the school level.
     */
    public function schoolComments()
    {
        $statuses = \App\School::with(['statusUpdate'])->get()->map(function ($school) {
            return $school->statusUpdate->id;
        })->toArray();
        return $this->hasManyDeepFromRelations($this->schools(), (new School)->comments())->whereIn('school_status_updates.id', $statuses);
    }

    /**
     * Get all of the school for the school level.
     */
    public function students()
    {
        $statuses = \App\School::with(['statusUpdate'])->get()->map(function ($school) {
            return $school->statusUpdate->id;
        })->toArray();
        return $this->hasManyDeepFromRelations($this->schools(), (new School)->students())->whereIn('school_status_updates.id', $statuses);
    }
}
