<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\Uuids;

class DocumentPic extends Pivot
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'document_pics';

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
     * Get the document that owns the document pic.
     */
    public function document()
    {
        return $this->belongsTo('App\Document');
    }

    /**
     * Get the pic that owns the document pic.
     */
    public function pic()
    {
        return $this->belongsTo('App\Pic');
    }
}
