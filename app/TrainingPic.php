<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class TrainingPic extends Pivot
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'training_pics';

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
     * Get the training that owns the training pic.
     */
    public function training()
    {
        return $this->belongsTo('App\Training');
    }

    /**
     * Get the pic that owns the training pic.
     */
    public function pic()
    {
        return $this->belongsTo('App\Pic');
    }
}
