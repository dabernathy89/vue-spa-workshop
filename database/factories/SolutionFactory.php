<?php

use Faker\Generator as Faker;

$factory->define(App\Solution::class, function (Faker $faker) {
    return [
        'image' => 'https://picsum.photos/' . $faker->numberBetween(300, 900) . '/' . $faker->numberBetween(300, 900) . '/?random'
    ];
});
