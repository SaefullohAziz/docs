<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Datakrama\Eloquid\Traits\Uuids;

class VisitationDestination extends Model
{
    use Uuids;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    /**
     * Get the school that owns the visitation destination.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }
}
