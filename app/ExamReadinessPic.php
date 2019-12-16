<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Datakrama\Eloquid\Traits\Uuids;

class ExamReadinessPic extends Pivot
{
    use Uuids;
    
    /**
     * Get the exam readiness that owns the exam readiness pic.
     */
    public function examReadiness()
    {
        return $this->belongsTo('App\ExamReadiness');
    }

    /**
     * Get the pic that owns the exam readiness pic.
     */
    public function pic()
    {
        return $this->belongsTo('App\Pic');
    }
}
