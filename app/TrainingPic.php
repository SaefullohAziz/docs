<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingPic extends Model
{
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
