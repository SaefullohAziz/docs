<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Province extends Model
{
    use Uuids;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'abbreviation'
    ];

    /**
     * Get the regency for the province.
     */
    public function regency()
    {
        return $this->hasMany('App\Regency');
    }

    /**
     * Get the island that owns the province.
     */
    public function island()
    {
        return $this->belongsTo('App\Island');
    }
}
