<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Datakrama\Eloquid\Traits\Uuids;

class SchoolComment extends Model
{
    use Uuids;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['school_id', 'staff_id', 'message'];

    /**
     * Get the comment for the school.
     */
    public function attachments()
    {
        return $this->hasMany('App\SchoolCommentAttachment');
    }

    /**
     * Get the school that owns the comment.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    /**
     * Get the staff that owns the comment.
     */
    public function staff()
    {
        return $this->belongsTo('App\Admin\User');
    }
}
