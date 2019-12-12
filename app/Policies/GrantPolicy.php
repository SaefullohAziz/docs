<?php

namespace App\Policies;

use App\User;
use App\Grant;
use Illuminate\Auth\Access\HandlesAuthorization;

class GrantPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any grants.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the grant.
     *
     * @param  \App\User  $user
     * @param  \App\Grant  $grant
     * @return mixed
     */
    public function view(User $user, Grant $grant)
    {
        return $user->school_id === $grant->school_id;
    }

    /**
     * Determine whether the user can create grants.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the grant.
     *
     * @param  \App\User  $user
     * @param  \App\Grant  $grant
     * @return mixed
     */
    public function update(User $user, Grant $grant)
    {
        return $user->school_id === $grant->school_id;
    }

    /**
     * Determine whether the user can delete the grant.
     *
     * @param  \App\User  $user
     * @param  \App\Grant  $grant
     * @return mixed
     */
    public function delete(User $user, Grant $grant)
    {
        //
    }

    /**
     * Determine whether the user can restore the grant.
     *
     * @param  \App\User  $user
     * @param  \App\Grant  $grant
     * @return mixed
     */
    public function restore(User $user, Grant $grant)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the grant.
     *
     * @param  \App\User  $user
     * @param  \App\Grant  $grant
     * @return mixed
     */
    public function forceDelete(User $user, Grant $grant)
    {
        //
    }
}
