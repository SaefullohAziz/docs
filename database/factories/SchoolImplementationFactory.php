<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Department;
use App\SchoolImplementation;
use Faker\Generator as Faker;

$factory->define(SchoolImplementation::class, function (Faker $faker) {
    $department = Department::pluck('id')->random();
    return [
        'department_id' => $department,
    ];
});
