<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class SchoolCommentAttachment extends Model
{
    use Uuids;
    
    /**
     * Get the school comment that owns the attachment.
     */
    public function comment()
    {
        return $this->belongsTo('App\SchoolComment');
    }
}
