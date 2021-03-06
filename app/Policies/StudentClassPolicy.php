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
        return $user->school->implementations->count();
    }

    /**
     * Determine whether the user can create students.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function createStudent(User $user, StudentClass $studentClass)
    {
        if (empty($studentClass->closed_at)) {
            if ($studentClass->school_year != schoolYear()) {
                $studentClass->fill(['closed_at' => now()]);
                $studentClass->save();
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create students.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function adminCreateStudent(Staff $staff, StudentClass $studentClass)
    {
        if (empty($studentClass->closed_at)) {
            if ($studentClass->school_year != schoolYear()) {
                $studentClass->closed_at = now();
                $studentClass->save();
                return false;
            }
            return true;
        }
        return false;
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
        return $studentClass->students->count() == 0;
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
        return $studentClass->students->count() == 0;
    }

    /**
     * Determine whether the user can create students.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function updateStudent(User $user, StudentClass $studentClass)
    {
        if (empty($studentClass->closed_at)) {
            if ($studentClass->school_year != schoolYear()) {
                $studentClass->closed_at = now();
                $studentClass->save();
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create students.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function adminUpdateStudent(Staff $staff, StudentClass $studentClass)
    {
        if (empty($studentClass->closed_at)) {
            if ($studentClass->school_year != schoolYear()) {
                $studentClass->closed_at = now();
                $studentClass->save();
                return false;
            }
            return true;
        }
        return false;
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
     * Determine whether the user can delete the student class.
     *
     * @param  \App\User  $user
     * @param  \App\StudentClass  $studentClass
     * @return mixed
     */
    public function deleteStudent(User $user, StudentClass $studentClass)
    {
        return $user->school_id === $studentClass->school_id;
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
