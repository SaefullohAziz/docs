<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class SchoolStatusUpdate extends Model
{
    use Uuids;
    
    /**
     * Get the school that owns the status update.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    /**
     * Get the status that owns the status update.
     */
    public function status()
    {
        return $this->belongsTo('App\SchoolStatus');
    }
}
