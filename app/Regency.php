<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code'
    ];

    /**
     * Get the province that owns the regency.
     */
    public function province()
    {
        return $this->belongsTo('App\Province');
    }

    /**
     * Scope a query to only include specific status of given name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetByProvinceName($query, $name)
    {
        return $query->whereHas('province', function ($subQuery) use ($name) {
            $subQuery->where('name', $name);
        });
    }
}
