<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class TrainingPic extends Model
{
    use Uuids;
    
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
