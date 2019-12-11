<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\Uuids;

class Grant extends Model
{
    use Uuids, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the school that owns the grant.
     */
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    /**
     * Get the grant status for the grant.
     */
    public function grantStatuses()
    {
        return $this->hasMany('App\GrantStatus');
    }

    /**
     * Get the latest grant status for the grant.
     */
    public function grantStatus()
    {
        return $this->hasOne('App\GrantStatus')->orderBy('created_at', 'desc')->limit(1);
    }

    /**
     * The status that belong to the grant.
     */
    public function statuses()
    {
        return $this->belongsToMany('App\Status', 'grant_statuses')->using('App\GrantStatus')->withTimestamps();
    }

    /**
     * Get the grant pic for the grant.
     */
    public function grantPic()
    {
        return $this->hasOne('App\GrantPic');
    }

    /**
     * The pic that belong to the grant.
     */
    public function pic()
    {
        return $this->belongsToMany('App\Pic', 'grant_pics')->using('App\GrantPic')->withTimestamps();
    }
}
