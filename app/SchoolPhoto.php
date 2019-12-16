<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Datakrama\Eloquid\Traits\Uuids;

class SchoolPhoto extends Model
{
    use Uuids;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id', 'category', 'name', 'description'
    ];

    /**
     * Get the school that owns the photo.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }
}
