<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Province;
use App\Regency;
use App\PoliceNumber;
use App\Department;
use App\School;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(School::class, function (Faker $faker) {
    $gender = $faker->randomElement(['male', 'female']);
    $province = $faker->randomElement(Province::pluck('name')->toArray());
    $regencies = Regency::join('provinces', 'regencies.province_id', '=', 'provinces.id')->where('provinces.name', $province)->pluck('regencies.name')->toArray();
    $regency = $faker->randomElement($regencies);
    $policeNumbers = PoliceNumber::pluck('name')->toArray();
    $departments = Department::pluck('name')->toArray();
    return [
        'type' => $faker->randomElement(['Negeri', 'Swasta']),
        'name' => 'SMK Negeri ' . $faker->numberBetween(1, 99) . ' ' . $regency,
        'address' => $faker->address,
        'province' => $province, 
        'regency' => $regency, 
        'police_number' => $faker->randomElement($policeNumbers), 
        'since' => $faker->year('1998'), 
        'school_phone_number' => $faker->unique()->phoneNumber,
        'school_email' => $faker->unique()->safeEmail, 
        'school_web' => 'http://' . $faker->domainName, 
        'total_student' => $faker->numberBetween(900, 1500), 
        'department' => $faker->randomElement($departments), 
        'iso_certificate' => $faker->randomElement(['Sudah', 'Belum']), 
        'mikrotik_academy' => $faker->randomElement(['Sudah', 'Belum']), 
        'headmaster_name' => $faker->unique()->name($gender), 
        'headmaster_phone_number' => $faker->unique()->phoneNumber, 
        'headmaster_email' => $faker->unique()->safeEmail, 
        'reference' => $faker->randomElement(['Sekolah Peserta / Sekolah Binaan', 'Internet (Facebook Page/Web)']), 
        'dealer_name' => null, 
        'dealer_phone_number' => null, 
        'dealer_email' => null, 
        'proposal' => $faker->randomElement(['Sudah', 'Belum']), 
        'code' => Str::random(10),
    ];
});

