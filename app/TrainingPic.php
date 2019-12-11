<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class TrainingPic extends Pivot
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
