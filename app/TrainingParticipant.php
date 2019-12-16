<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Datakrama\Eloquid\Traits\Uuids;

class TrainingParticipant extends Pivot
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
