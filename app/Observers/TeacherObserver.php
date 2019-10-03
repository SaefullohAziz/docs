<?php

namespace App\Observers;

use App\Teacher;
use App\Regency;

class TeacherObserver
{
    /**
     * Handle the teacher "created" event.
     *
     * @param  \App\Teacher  $teacher
     * @return void
     */
    public function created(Teacher $teacher)
    {
        $code = $teacher->school->code;
        $regency = Regency::where('name', $teacher->school->regency)->first();
        $teachers = Teacher::where('school_id', $teacher->school_id)->whereNotNull('username')->orderBy('username', 'desc')->first();
        $username = $regency->code.$code.'-001';
        $rules = [
            1 => '00',
            2 => '0',
            3 => ''
        ];
        if ($teachers) {
            $number = (substr($teachers->username, -3)+1);
            $username = $regency->code.$code.'-'.$rules[strlen($number)].$number;
        }
        $teacher->fill([
            'username' => $username
        ]);
        $teacher->save();
    }

    /**
     * Handle the teacher "updated" event.
     *
     * @param  \App\Teacher  $teacher
     * @return void
     */
    public function updated(Teacher $teacher)
    {
        //
    }

    /**
     * Handle the teacher "deleted" event.
     *
     * @param  \App\Teacher  $teacher
     * @return void
     */
    public function deleted(Teacher $teacher)
    {
        //
    }

    /**
     * Handle the teacher "restored" event.
     *
     * @param  \App\Teacher  $teacher
     * @return void
     */
    public function restored(Teacher $teacher)
    {
        //
    }

    /**
     * Handle the teacher "force deleted" event.
     *
     * @param  \App\Teacher  $teacher
     * @return void
     */
    public function forceDeleted(Teacher $teacher)
    {
        //
    }
}
