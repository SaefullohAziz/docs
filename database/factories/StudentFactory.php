<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Province;
use App\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    $gender = $faker->randomElement(['male', 'female']);
    $provinces = Province::pluck('name')->toArray();
    $parentEducations = ['SMA / Sederajat', 'S1', 'S2', 'S3'];
    $parentEarnings = ['0', 'Kurang dari 1.000.000', '1.000.000 - 2.000.000', 'Lebih dari 2.000.000'];
    $economyStatuses = ['Menengah Bawah', 'Menengah', 'Menengah Atas'];
    $religions = ['Islam', 'Kristen', 'Katholik', 'Budha', 'Hindu', 'Lain-Lain'];
    $bloodTypes = ['A', 'AB', 'B', 'O'];
    $mileages = ['Kurang dari 1 Km', 'Lebih dari 1 Km'];
    return [
        'name' => $faker->name($gender),
        'nickname' => $faker->firstName($gender),
        'province' => $faker->randomElement($provinces),
        'nisn' => $faker->unique()->numberBetween(100000000, 99999999999),
        'email' => $faker->unique()->safeEmail,
        'gender' => $faker->randomElement(['Laki-Laki', 'Perempuan']),
        'father_name' => $faker->name('male'),
        'father_education' => $faker->randomElement($parentEducations),
        'father_earning' => $faker->randomElement($parentEarnings),
        'father_earning_nominal' => $faker->randomElement(['100000', '1000000', '10000000', '1000000000']),
        'mother_name' => $faker->name('female'),
        'mother_education' => $faker->randomElement($parentEducations),
        'mother_earning' => $faker->randomElement($parentEarnings),
        'mother_earning_nominal' => $faker->randomElement(['100000', '1000000', '10000000', '1000000000']),
        'trustee_name' => $faker->name($gender),
        'trustee_education' => $faker->randomElement($parentEducations),
        'economy_status' => $faker->randomElement($economyStatuses),
        'religion' => $faker->randomElement($religions),
        'blood_type' => $faker->randomElement($bloodTypes),
        'special_need' => $faker->randomElement(['Ya', 'Tidak']),
        'mileage' => $faker->randomElement($mileages),
        'distance' => $faker->numberBetween(1, 200),
        'diploma_number' => $faker->unique()->numberBetween(100000000000, 999999999999),
        'height' => $faker->numberBetween(150, 200),
        'weight' => $faker->numberBetween(50, 100),
        'child_order' => 1,
        'sibling_number' => 0,
        'stepbrother_number' => 0,
        'step_sibling_number' => 0,
        'dateofbirth' => $faker->date('Y-m-d', '2004-01-01'),
        'address' => $faker->address,
        'father_address' => $faker->address,
        'trustee_address' => $faker->address,
        'phone_number' => $faker->unique()->phoneNumber,
        'photo' => 'default.png',
        'computer_basic_score' => $faker->numberBetween(60, 100),
        'intelligence_score' => $faker->numberBetween(60, 100),
        'reasoning_score' => $faker->numberBetween(60, 100),
        'analogy_score' => $faker->numberBetween(60, 100),
        'numerical_score' => $faker->numberBetween(60, 100),
    ];
});
