<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolPic extends Model
{
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
