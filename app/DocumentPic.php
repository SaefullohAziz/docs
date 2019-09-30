<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class DocumentPic extends Model
{
    use Uuids;
    
    /**
     * Get the document that owns the document pic.
     */
    public function document()
    {
        return $this->belongsTo('App\Document');
    }

    /**
     * Get the pic that owns the document pic.
     */
    public function pic()
    {
        return $this->belongsTo('App\Pic');
    }
}
