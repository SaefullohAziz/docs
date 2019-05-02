<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolStatusUpdate extends Model
{
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
