<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class TrainingParticipant extends Pivot
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'training_participants';

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
     * Get the training that owns the training participant.
     */
    public function training()
    {
        return $this->belongsTo('App\Training');
    }

    /**
     * Get the participant that owns the training participant.
     */
    public function participant()
    {
        return $this->belongsTo('App\Teacher', 'teacher_id');
    }
}
