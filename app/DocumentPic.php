<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentPic extends Model
{
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
