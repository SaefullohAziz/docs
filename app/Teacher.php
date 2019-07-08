<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    /**
     * Get the training participant for the participant.
     */
    public function trainingParticipant()
    {
        return $this->hasMany('App\SspStudent');
    }

    /**
     * The subsidy that belong to the student.
     */
    public function training()
    {
        return $this->belongsToMany('App\Training', 'training_participants', 'teacher_id');
    }

    /**
     * Scope a query to only include specific teacher of given school.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySchool($query, $school)
    {
        return $query->where('school_id', $school);
    }
}
