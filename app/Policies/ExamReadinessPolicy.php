<?php

namespace App\Policies;

use App\User;
use App\ExamReadiness;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExamReadinessPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any exam readinesses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the exam readiness.
     *
     * @param  \App\User  $user
     * @param  \App\ExamReadiness  $examReadiness
     * @return mixed
     */
    public function view(User $user, ExamReadiness $examReadiness)
    {
        return $user->school_id === $examReadiness->school_id;
    }

    /**
     * Determine whether the user can create exam readinesses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        $student = \App\Student::has('subsidy')->whereHas('class', function ($query) {
            $query->where('school_id', auth()->user()->school->id);
        })->get();
        return $student->count() > 0;
    }

    /**
     * Determine whether the user can update the exam readiness.
     *
     * @param  \App\User  $user
     * @param  \App\ExamReadiness  $examReadiness
     * @return mixed
     */
    public function update(User $user, ExamReadiness $examReadiness)
    {
        return $user->school_id === $examReadiness->school_id;
    }

    /**
     * Determine whether the user can delete the exam readiness.
     *
     * @param  \App\User  $user
     * @param  \App\ExamReadiness  $examReadiness
     * @return mixed
     */
    public function delete(User $user, ExamReadiness $examReadiness)
    {
        //
    }

    /**
     * Determine whether the user can restore the exam readiness.
     *
     * @param  \App\User  $user
     * @param  \App\ExamReadiness  $examReadiness
     * @return mixed
     */
    public function restore(User $user, ExamReadiness $examReadiness)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the exam readiness.
     *
     * @param  \App\User  $user
     * @param  \App\ExamReadiness  $examReadiness
     * @return mixed
     */
    public function forceDelete(User $user, ExamReadiness $examReadiness)
    {
        //
    }
}
