<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class SubsidyStatus extends Pivot
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subsidy_statuses';

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
        });
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['subsidy_id', 'status_id', 'log_id', 'paid_at', 'invoice', 'starting_price', 'paid_installment', 'lack_of_price', 'description'];

    /**
     * Get the subsidy that owns the subsidy status.
     */
    public function subsidy()
    {
        return $this->belongsTo('App\Subsidy');
    }

    /**
     * Get the status that owns the subsidy status.
     */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    /**
     * Get the activity log that owns the subsidy status.
     */
    public function log()
    {
        return $this->belongsTo('App\ActivityLog', 'log_id');
    }
}
