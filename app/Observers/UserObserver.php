<?php

namespace App\Observers;

use App\User;
use App\Regency;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        $code = $user->school->code;
        $regency = Regency::where('name', $user->school->regency)->first();
        $users = User::where('school_id', $user->school_id)->get();
        $username = $regency->code.$code;
        if ($users->count() > 1) {
            User::where('school_id', $user->school_id)->where('id', '!=', $user->id)->delete();
        }
        $user->fill([
            'username' => $username
        ]);
        $user->save();
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
