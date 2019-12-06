<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Uuids;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class SchoolLevel extends Model
{
    use Uuids, HasRelationships;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order', 'asc');
        });
    }
    
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
        return $this->hasManyThrough('App\SchoolStatusUpdate', 'App\SchoolStatus')->whereRaw('NOT EXISTS (SELECT 1 FROM school_status_updates t2 WHERE t2.school_id = school_status_updates.school_id AND t2.created_at > school_status_updates.created_at)');
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
        return $this->hasManyDeepFromRelations($this->statusUpdate(), (new SchoolStatusUpdate)->school())->whereRaw('NOT EXISTS (SELECT 1 FROM school_status_updates t2 WHERE t2.school_id = school_status_updates.school_id AND t2.created_at > school_status_updates.created_at)');
    }

    /**
     * Get all of the school comments for the school level.
     */
    public function schoolComments()
    {
        return $this->hasManyDeepFromRelations($this->schools(), (new School)->comments())->whereRaw('NOT EXISTS (SELECT 1 FROM school_status_updates t2 WHERE t2.school_id = school_status_updates.school_id AND t2.created_at > school_status_updates.created_at)');
    }

    /**
     * Get all of the school for the school level.
     */
    public function students()
    {
        return $this->hasManyDeepFromRelations($this->schools(), (new School)->students())->whereRaw('NOT EXISTS (SELECT 1 FROM school_status_updates t2 WHERE t2.school_id = school_status_updates.school_id AND t2.created_at > school_status_updates.created_at)');
    }
}
