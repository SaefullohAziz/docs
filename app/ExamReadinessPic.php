<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class ExamReadinessPic extends Pivot
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exam_readiness_pics';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        Pivot::creating(function($pivot) {
            $pivot->id = (string) \Illuminate\Support\Str::uuid();
            $pivot->created_at = now();
            $pivot->updated_at = now();
        });
    }
    
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
