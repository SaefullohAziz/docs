<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class AudienceParticipant extends Pivot
{
    use Uuids;
    
    /**
     * Get the audience that owns the audience participant.
     */
    public function audience()
    {
        return $this->belongsTo('App\Attendance', 'teacher_id', 'teacher_id');
    }

    /**
     * Get the participant that owns the audience participant.
     */
    public function participant()
    {
        return $this->belongsTo('App\Teacher', 'teacher_id');
    }
}
