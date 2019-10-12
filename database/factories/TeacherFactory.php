<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Teacher;
use Faker\Generator as Faker;

$factory->define(Teacher::class, function (Faker $faker) {
	// $status = $faker->randomElement(['yes', 'no']);
	// $statuses = ['yes' => 'yes', 'no' => 'no'];
    $gender = $faker->randomElement(['male', 'female']);
    $genders = ['male' => 'Laki-Laki', 'female' => 'Perempuan'];
    return [
        'name' => $faker->name($gender),
        // 'gender' => $genders[$gender],
        'position' => 'Guru Umum',
        // 'teaching_status' => $statuses[$status],
        // 'date_of_birth' => $faker->dateTimeBetween('1970-01-01', '2000-12-30')
    						// ->format('d/m/Y'),
        'phone_number' => $faker->unique()->phoneNumber,
        'email' => $faker->unique()->safeEmail,
    ];
});
