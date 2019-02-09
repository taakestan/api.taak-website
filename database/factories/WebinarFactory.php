<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Webinar::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'en_title' => $faker->unique()->safeEmail,
    ];
});
