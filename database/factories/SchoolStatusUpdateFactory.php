<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Admin\User as Staff;
use App\SchoolStatus;
use App\SchoolStatusUpdate;
use Faker\Generator as Faker;

$factory->define(SchoolStatusUpdate::class, function (Faker $faker) {
    $statuses = SchoolStatus::pluck('id')->toArray();
    $user = Staff::where('username', 'admin')->first();
    return [
        'school_status_id' => $faker->randomElement($statuses),
        'created_by' => 'staff',
        'staff_id' => $user->id,
    ];
});
