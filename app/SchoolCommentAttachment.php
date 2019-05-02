<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolCommentAttachment extends Model
{
    /**
     * Get the school comment that owns the attachment.
     */
    public function comment()
    {
        return $this->belongsTo('App\SchoolComment');
    }
}
