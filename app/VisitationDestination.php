<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class VisitationDestination extends Model
{
    use Uuids;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    protected $fillable = ['school_id'];
    
    /**
     * Get the school that owns the visitation destination.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }
}
