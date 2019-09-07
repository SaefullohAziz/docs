<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\StudentClass;
use Faker\Generator as Faker;

$factory->define(StudentClass::class, function (Faker $faker) {
    return [
        'generation' => 'Angkatan 1',
        'school_year' => '2018/2019',
        'grade' => 'Kelas 10'
    ];
});
