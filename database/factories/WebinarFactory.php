<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Webinar::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'label' => $faker->unique()->safeEmail,
        'description' => $faker->sentence,
        'content' => $faker->paragraph,
    ];
});
