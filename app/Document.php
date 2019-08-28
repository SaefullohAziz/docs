<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;
    
    /**
     * Get the school that owns the document.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    /**
     * Get the document status for the document.
     */
    public function documentStatus()
    {
        return $this->hasMany('App\DocumentStatus');
    }

     /**
     * Get the latest document status for the document.
     */
    public function latestDocumentStatus()
    {
        return $this->hasOne('App\DocumentStatus')->orderBy('id', 'desc')->limit(1);
    }

    /**
     * The status that belong to the document.
     */
    public function status()
    {
        return $this->belongsToMany('App\Status', 'document_statuses');
    }

    /**
     * Get the document pic for the document.
     */
    public function documentPic()
    {
        return $this->hasOne('App\DocumentPic');
    }

    /**
     * The pic that belong to the document.
     */
    public function pic()
    {
        return $this->belongsToMany('App\Pic', 'document_pics');
    }
}
