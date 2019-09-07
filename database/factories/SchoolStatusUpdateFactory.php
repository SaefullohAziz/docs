<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Admin\User as Staff;
use App\SchoolStatus;
use App\SchoolStatusUpdate;
use Faker\Generator as Faker;

$factory->define(SchoolStatusUpdate::class, function (Faker $faker) {
    $status = SchoolStatus::byName('Daftar')->first();
    $user = Staff::where('username', 'admin')->first();
    return [
        'school_status_id' => $status->id,
        'created_by' => 'staff',
        'staff_id' => $user->id,
    ];
});
