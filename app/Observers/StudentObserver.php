<?php

namespace App\Observers;

use App\Student;
use App\Regency;

class StudentObserver
{
    /**
     * Handle the student "created" event.
     *
     * @param  \App\Student  $student
     * @return void
     */
    public function created(Student $student)
    {
        $code = $student->class->school->code.'-'.substr($student->class->school_year, 2, 2).substr($student->class->school_year, -2);
        $regency = Regency::where('name', $student->class->school->regency)->first();
        $students = Student::whereHas('class', function ($query) use ($student) {
            $query->where('school_id', $student->class->school_id);
            $query->where('school_year', $student->class->school_year);
        })->whereNotNull('username')->orderBy('username', 'desc')->first();
        $username = $regency->code.$code.'01';
        $rules = [
            1 => '0',
            2 => '',
        ];
        if ($students) {
            $number = (substr($students->username, -2)+1);
            $username = $regency->code.$code.$rules[strlen($number)].$number;
        }
        $student->fill([
            'username' => $username
        ]);
        $student->save();
    }

    /**
     * Handle the student "updated" event.
     *
     * @param  \App\Student  $student
     * @return void
     */
    public function updated(Student $student)
    {
        //
    }

    /**
     * Handle the student "deleted" event.
     *
     * @param  \App\Student  $student
     * @return void
     */
    public function deleted(Student $student)
    {
        //
    }

    /**
     * Handle the student "restored" event.
     *
     * @param  \App\Student  $student
     * @return void
     */
    public function restored(Student $student)
    {
        //
    }

    /**
     * Handle the student "force deleted" event.
     *
     * @param  \App\Student  $student
     * @return void
     */
    public function forceDeleted(Student $student)
    {
        //
    }
}
