<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class GrantPic extends Pivot
{
    use Uuids;
    
    /**
     * Get the grant that owns the grant pic.
     */
    public function grant()
    {
        return $this->belongsTo('App\Grant');
    }

    /**
     * Get the pic that owns the grant pic.
     */
    public function pic()
    {
        return $this->belongsTo('App\Pic');
    }
}
