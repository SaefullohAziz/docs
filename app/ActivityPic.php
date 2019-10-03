<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class ActivityPic extends Pivot
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_pics';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        Pivot::creating(function($pivot) {
            $pivot->id = (string) \Illuminate\Support\Str::uuid();
        });
    }
    
    /**
     * Get the activity that owns the activity pic.
     */
    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }

    /**
     * Get the pic that owns the activity pic.
     */
    public function pic()
    {
        return $this->belongsTo('App\Pic');
    }
}
