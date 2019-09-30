<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class ExamReadinessPic extends Model
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
