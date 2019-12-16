<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Datakrama\Eloquid\Traits\Uuids;

class DocumentPic extends Pivot
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
