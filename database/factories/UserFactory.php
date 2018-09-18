<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'api_token' => str_random(60)
    ];
});

$factory->afterCreatingState(App\User::class, 'Participant', function ($user, $faker) {
    $owner = factory(App\User::class)->create();
    $hunts = factory(App\Hunt::class, $faker->numberBetween(5, 10))->create(['owner_id' => $owner->id]);
    $user->hunts()->attach($hunts);
});

$factory->afterCreatingState(App\User::class, 'Owner', function ($user, $faker) {
    factory(App\Hunt::class, $faker->numberBetween(1, 3))->create(['owner_id' => $user->id]);
});
