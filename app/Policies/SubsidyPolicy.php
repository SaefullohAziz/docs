<?php

namespace App\Policies;

use App\Admin\User as Staff;
use App\User;
use App\Subsidy;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubsidyPolicy
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
     * Determine whether the user can view any subsidies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the subsidy.
     *
     * @param  \App\User  $user
     * @param  \App\Subsidy  $subsidy
     * @return mixed
     */
    public function view(User $user, Subsidy $subsidy)
    {
        return $user->school_id === $subsidy->school_id;
    }

    /**
     * Determine whether the user can create subsidies.
     *
     * @param  \App\Admin\User  $user
     * @return mixed
     */
    public function adminCreate(Staff $user)
    {
        return \Gate::allows('create-subsidy');
    }

    /**
     * Determine whether the user can create subsidies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return \Gate::allows('create-subsidy');
    }

    /**
     * Determine whether the user can update the subsidy.
     *
     * @param  \App\User  $user
     * @param  \App\Subsidy  $subsidy
     * @return mixed
     */
    public function update(User $user, Subsidy $subsidy)
    {
        return $user->school_id === $subsidy->school_id;
    }

    /**
     * Determine whether the user can delete the subsidy.
     *
     * @param  \App\User  $user
     * @param  \App\Subsidy  $subsidy
     * @return mixed
     */
    public function delete(User $user, Subsidy $subsidy)
    {
        return $user->school_id === $subsidy->school_id;
    }

    /**
     * Determine whether the user can restore the subsidy.
     *
     * @param  \App\User  $user
     * @param  \App\Subsidy  $subsidy
     * @return mixed
     */
    public function restore(User $user, Subsidy $subsidy)
    {
        return $user->school_id === $subsidy->school_id;
    }

    /**
     * Determine whether the user can permanently delete the subsidy.
     *
     * @param  \App\User  $user
     * @param  \App\Subsidy  $subsidy
     * @return mixed
     */
    public function forceDelete(User $user, Subsidy $subsidy)
    {
        return $user->school_id === $subsidy->school_id;
    }
}
