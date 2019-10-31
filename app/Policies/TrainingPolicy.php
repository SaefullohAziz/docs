<?php

namespace App\Policies;

use App\Admin\User as Staff;
use App\User;
use App\Training;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingPolicy
{
    use HandlesAuthorization;

    /** 
     * Authorizing specific user to perform any action.
     * 
     * @param  \App\User  $user
     * @return mixed
     */
    public function before($user, $ability)
    {
        
    }
    
    /**
     * Determine whether the user can view any trainings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the training.
     *
     * @param  \App\User  $user
     * @param  \App\Training  $training
     * @return mixed
     */
    public function view(User $user, Training $training)
    {
        return $user->school_id === $training->school_id;
    }

    /**
     * Determine whether the user can create trainings.
     *
     * @param  \App\Admin\User  $user
     * @return mixed
     */
    public function adminCreate(Staff $user)
    {
        return \Gate::allows('create-training');
    }

    /**
     * Determine whether the user can create trainings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if (\Gate::allows('create-training')) {
            return $user->school()->has('teachers')->first();
        }
        return false;
    }

    /**
     * Determine whether the user can update the training.
     *
     * @param  \App\User  $user
     * @param  \App\Training  $training
     * @return mixed
     */
    public function update(User $user, Training $training)
    {
        return $user->school_id === $training->school_id;
    }

    /**
     * Determine whether the user can delete the training.
     *
     * @param  \App\User  $user
     * @param  \App\Training  $training
     * @return mixed
     */
    public function delete(User $user, Training $training)
    {
        return $user->school_id === $training->school_id;
    }

    /**
     * Determine whether the user can restore the training.
     *
     * @param  \App\User  $user
     * @param  \App\Training  $training
     * @return mixed
     */
    public function restore(User $user, Training $training)
    {
        return $user->school_id === $training->school_id;
    }

    /**
     * Determine whether the user can permanently delete the training.
     *
     * @param  \App\User  $user
     * @param  \App\Training  $training
     * @return mixed
     */
    public function forceDelete(User $user, Training $training)
    {
        return $user->school_id === $training->school_id;
    }
}
