<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class SchoolPic extends Pivot
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'school_pics';

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
     * Get the school that owns the school pic.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    /**
     * Get the pic that owns the school pic.
     */
    public function pic()
    {
        return $this->belongsTo('App\Pic');
    }
}
