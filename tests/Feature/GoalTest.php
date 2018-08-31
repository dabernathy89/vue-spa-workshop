<?php
// phpcs:disable PSR1.Methods.CamelCapsMethodName

namespace Tests\Feature;

use App\User;
use App\Hunt;
use App\Goal;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GoalTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_owner_can_add_goals_to_a_hunt()
    {
        $user = factory(User::class)->states('Owner')->create();
        $hunt = $user->ownedHunts->first();

        $response = $this->actingAs($user)
            ->post(route('hunt.goal.store', ['hunt' => $hunt->id]), ['title' => 'Dictumst eleifend integer']);

        $goal = $hunt->goals()->get()->first();
        $this->assertSame('Dictumst eleifend integer', $goal->title);
        $response->assertSessionHas('success', 'You successfully added the goal "' . $goal->title . '".');
    }

    public function test_a_goal_title_must_be_unique()
    {
        $user = factory(User::class)->states('Owner')->create();
        $hunt = $user->ownedHunts->first();
        $hunt->update(['status' => 'open']);
        $hunt->goals()->save(Goal::make(['title' => 'Dictumst eleifend integer']));

        $response = $this->actingAs($user)
            ->post(route('hunt.goal.store', ['hunt' => $hunt->id]), ['title' => 'Dictumst eleifend integer']);

        $this->assertSame(1, $hunt->goals()->count());
        $response->assertSessionHasErrors(['title']);
    }

    public function test_a_participant_cannot_add_goals_to_a_hunt()
    {
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();

        $response = $this->actingAs($user)
            ->post(route('hunt.goal.store', ['hunt' => $hunt->id]), ['title' => 'Dictumst eleifend integer']);

        $this->assertNull($hunt->goals()->get()->first());
        $response->assertStatus(403);
    }

    public function test_a_user_can_see_goals_on_a_hunt()
    {
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts()->first();
        factory(Goal::class, 5)->create(['hunt_id' => $hunt->id]);

        $response = $this->actingAs($user)
            ->get(route('hunt.show', ['hunt' => $hunt->id]));

        $response->assertSeeTextInOrder($hunt->goals->pluck('title')->all());
    }

    public function test_an_owner_can_delete_goals_from_a_hunt()
    {
        $user = factory(User::class)->states('Owner')->create();
        $hunt = $user->ownedHunts->first();
        $hunt->update(['status' => 'open']);
        $goal = factory(Goal::class)->create(['hunt_id' => $hunt->id]);

        $response = $this->actingAs($user)
            ->delete(route('hunt.goal.delete', ['hunt' => $hunt->id, 'goal' => $goal->id]));

        $this->assertNull($hunt->goals()->get()->first());
        $response->assertSessionHas('success', 'You successfully deleted the goal "' . $goal->title . '".');
    }

    public function test_a_participant_cannot_delete_goals_from_a_hunt()
    {
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();
        $goal = factory(Goal::class)->create(['hunt_id' => $hunt->id]);

        $response = $this->actingAs($user)
            ->delete(route('hunt.goal.delete', ['hunt' => $hunt->id, 'goal' => $goal->id]));

        $this->assertNotNull($hunt->goals()->get()->first());
        $response->assertStatus(403);
    }

    public function test_an_owner_cannot_delete_goals_in_a_closed_hunt()
    {
        $user = factory(User::class)->states('Owner')->create();
        $hunt = $user->ownedHunts->first();
        $goal = factory(Goal::class)->create(['hunt_id' => $hunt->id]);
        $hunt->update(['status' => 'closed']);

        $response = $this->actingAs($user)
            ->delete(route('hunt.goal.delete', ['hunt' => $hunt->id, 'goal' => $goal->id]));

        $this->assertNotNull($hunt->goals()->get()->first());
        $response->assertStatus(422);
    }

    public function test_an_owner_cannot_add_goals_to_a_closed_hunt()
    {
        $user = factory(User::class)->states('Owner')->create();
        $hunt = $user->ownedHunts->first();
        $hunt->update(['status' => 'closed']);

        $response = $this->actingAs($user)
            ->post(route('hunt.goal.store', ['hunt' => $hunt->id]), ['title' => 'Cursus potenti volutpat']);

        $this->assertNull($hunt->goals()->get()->first());
        $response->assertStatus(422);
    }
}
