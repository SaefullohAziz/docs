<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class SchoolPic extends Model
{
    use Uuids;
    
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
