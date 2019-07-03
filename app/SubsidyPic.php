<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubsidyPic extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['subsidy_id', 'pic_id'];

    /**
     * Get the subsidy that owns the subsidy pic.
     */
    public function subsidy()
    {
        return $this->belongsTo('App\Subsidy');
    }

    /**
     * Get the pic that owns the subsidy pic.
     */
    public function pic()
    {
        return $this->belongsTo('App\Pic');
    }
}
