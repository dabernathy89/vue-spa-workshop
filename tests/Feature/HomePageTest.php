<?php
// phpcs:disable PSR1.Methods.CamelCapsMethodName

namespace Tests\Feature;

use App\User;
use App\Hunt;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_see_their_owned_hunts()
    {
        $user = factory(User::class)->states('Owner')->create();
        $response = $this->actingAs($user)->get('/');

        $response->assertSeeTextInOrder($user->ownedHunts->pluck('name')->all());
    }

    public function test_a_user_cannot_see_other_users_owned_hunts()
    {
        $user1 = factory(User::class)->states('Owner')->create();
        $user2 = factory(User::class)->states('Owner')->create();
        $response = $this->actingAs($user1)->get('/');

        $user2->ownedHunts->pluck('name')->each(function ($name) use ($response) {
            $response->assertDontSeeText($name);
        });
    }

    public function test_a_user_can_see_hunts_they_participate_in()
    {
        $user = factory(User::class)->states('Participant')->create();
        $response = $this->actingAs($user)->get('/');

        $this->assertNotEmpty($user->hunts->all());
        $response->assertSeeTextInOrder($user->hunts->pluck('name')->all());
    }
}
