<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\VisitationDestination;
use App\School;
use Faker\Generator as Faker;

$factory->define(VisitationDestination::class, function (Faker $faker) {
	$schools = School::inRandomOrder()->limit(3)->pluck('id')->toArray();
    return [
        'school_id' => $faker->unique()->randomElement($schools),
    ];
});
