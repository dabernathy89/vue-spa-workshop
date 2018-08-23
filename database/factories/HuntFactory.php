<?php

use Faker\Generator as Faker;

$factory->define(App\Hunt::class, function (Faker $faker) {
    return [
        'name' => ucwords($faker->words(3, true)),
    ];
});
