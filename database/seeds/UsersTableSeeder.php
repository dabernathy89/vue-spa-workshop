<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        factory(App\User::class)->states('Owner')->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        factory(App\User::class, 5)->states('Owner')->create();
        $hunts = App\Hunt::get();

        factory(App\User::class, 100)
            ->create()
            ->each(function ($user) use ($hunts, $faker) {
                $num = $faker->numberBetween(0, 3);
                $some_hunts = $faker->randomElements($hunts->all(), $num);
                foreach ($some_hunts as $hunt) {
                    if ($hunt->owner_id !== $user->id) {
                        $user->hunts()->attach($hunt);
                    }
                }
            });
    }
}
