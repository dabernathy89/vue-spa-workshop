<?php

use Faker\Generator as Faker;

$factory->define(App\Goal::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence()
    ];
});

$factory->afterCreating(App\Goal::class, function ($goal, $faker) {
    if ($faker->boolean(40) && $goal->hunt->participants->count()) {
        $num_users = $faker->numberBetween(1, $goal->hunt->participants->count());
        $users = $faker->randomElements($goal->hunt->participants->all(), $num_users);
        collect($users)->each(function ($user) use ($goal) {
            factory(App\Solution::class)->create([
                'goal_id' => $goal->id,
                'user_id' => $user->id,
            ]);
        });
    }
});
