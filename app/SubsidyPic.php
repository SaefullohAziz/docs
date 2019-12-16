<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Datakrama\Eloquid\Traits\Uuids;

class SubsidyPic extends Pivot
{
    use Uuids;
    
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
