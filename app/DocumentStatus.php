<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentStatus extends Model
{
    /**
     * Get the document that owns the document status.
     */
    public function document()
    {
        return $this->belongsTo('App\Document');
    }

    /**
     * Get the status that owns the document status.
     */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    /**
     * Get the activity log that owns the document status.
     */
    public function log()
    {
        return $this->belongsTo('App\ActivityLog', 'log_id');
    }
}
