<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\User::class, function (Faker $faker) {
    return [
        'username' => $faker->unique()->userName,
        'name' => $faker->name,
        'password' => bcrypt('secret')
    ];
});
