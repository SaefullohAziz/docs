<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Teacher;
use Faker\Generator as Faker;

$factory->define(Teacher::class, function (Faker $faker) {
    $gender = $faker->randomElement(['male', 'female']);
    $genders = ['male' => 'Laki-Laki', 'female' => 'Perempuan'];
    return [
        'name' => $faker->name($gender),
        'position' => 'Guru Umum',
        'gender' => $genders[$gender],
        'phone_number' => $faker->unique()->phoneNumber,
        'email' => $faker->unique()->safeEmail,
    ];
});
