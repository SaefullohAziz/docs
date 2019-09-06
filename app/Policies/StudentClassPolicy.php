<?php

namespace App\Policies;

use App\Admin\User as Staff;
use App\User;
use App\School;
use App\StudentClass;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentClassPolicy
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
        if (auth()->guard('web')->check()) {
            $school = School::find(auth()->user()->school->id);
            if ( ! $school->implementation->count()) {
                return false;
            }
        }
        return false;
    }
    
    /**
     * Determine whether the user can view any student classes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        
    }

    /**
     * Determine whether the user can view the student class.
     *
     * @param  \App\User  $user
     * @param  \App\StudentClass  $studentClass
     * @return mixed
     */
    public function view(User $user, StudentClass $studentClass)
    {
        //
    }

    /**
     * Determine whether the user can create student classes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the student class.
     *
     * @param  \App\User  $user
     * @param  \App\StudentClass  $studentClass
     * @return mixed
     */
    public function update(User $user, StudentClass $studentClass)
    {
        //
    }

    /**
     * Determine whether the staff can update the student class.
     *
     * @param  \App\Admin\User  $staff
     * @param  \App\StudentClass  $studentClass
     * @return mixed
     */
    public function adminUpdate(Staff $staff, StudentClass $studentClass)
    {
        return $studentClass->student->count() == 0;
    }

    /**
     * Determine whether the user can delete the student class.
     *
     * @param  \App\User  $user
     * @param  \App\StudentClass  $studentClass
     * @return mixed
     */
    public function delete(User $user, StudentClass $studentClass)
    {
        //
    }

    /**
     * Determine whether the user can restore the student class.
     *
     * @param  \App\User  $user
     * @param  \App\StudentClass  $studentClass
     * @return mixed
     */
    public function restore(User $user, StudentClass $studentClass)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the student class.
     *
     * @param  \App\User  $user
     * @param  \App\StudentClass  $studentClass
     * @return mixed
     */
    public function forceDelete(User $user, StudentClass $studentClass)
    {
        //
    }
}
