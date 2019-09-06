<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SchoolPic;
use Faker\Generator as Faker;

$factory->define(SchoolPic::class, function (Faker $faker) {
    return [
        'pic_id' => function () {
            return factory(App\Pic::class)->create()->id;
        },
    ];
});
