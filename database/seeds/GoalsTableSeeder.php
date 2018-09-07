<?php

use App\Solution;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class GoalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        App\Hunt::get()->each(function ($hunt) use ($faker) {
            $goals = factory(App\Goal::class, $faker->numberBetween(4, 8))->create([
                'hunt_id' => $hunt->id,
            ]);

            $goals->each(function ($goal) use ($faker) {
                $goal->hunt->participants->shuffle()->take($faker->numberBetween(0, 3))->each(function ($participant) use ($goal, $faker) {
                    $goal->solutions()->save(Solution::make([
                        'image' => 'https://picsum.photos/' . $faker->numberBetween(300, 900) . '/' . $faker->numberBetween(300, 900) . '/?random',
                        'user_id' => $participant->id
                    ]));
                });
            });
        });
    }
}
