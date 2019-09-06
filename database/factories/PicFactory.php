<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Pic;
use Faker\Generator as Faker;

$factory->define(Pic::class, function (Faker $faker) {
    $gender = $faker->randomElement(['male', 'female']);
    return [
        'name' => $faker->name($gender),
        'position' => 'Guru',
        'phone_number' => $faker->unique()->phoneNumber,
        'email' => $faker->unique()->safeEmail
    ];
});
