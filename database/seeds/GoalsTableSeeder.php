<?php

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
            factory(App\Goal::class, $faker->numberBetween(4, 8))->create([
                'hunt_id' => $hunt->id,
            ]);
        });
    }
}
