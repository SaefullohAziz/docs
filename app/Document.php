<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Document extends Model
{
    use Uuids, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['notif'];
    
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
    public function documentStatuses()
    {
        return $this->hasMany('App\DocumentStatus');
    }

     /**
     * Get the latest document status for the document.
     */
    public function documentStatus()
    {
        return $this->hasOne('App\DocumentStatus')->orderBy('created_at', 'desc')->limit(1);
    }

    /**
     * The status that belong to the document.
     */
    public function statuses()
    {
        return $this->belongsToMany('App\Status', 'document_statuses')->using('App\DocumentStatus')->withTimestamps();
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
        return $this->belongsToMany('App\Pic', 'document_pics')->using('App\DocumentPic')->withTimestamps();
    }
}
