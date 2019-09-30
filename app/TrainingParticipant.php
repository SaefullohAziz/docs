<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class TrainingParticipant extends Model
{
    use Uuids;
    
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
