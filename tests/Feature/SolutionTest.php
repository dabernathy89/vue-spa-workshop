<?php
// phpcs:disable PSR1.Methods.CamelCapsMethodName

namespace Tests\Feature;

use App\User;
use App\Hunt;
use App\Goal;
use App\Solution;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SolutionTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_participant_can_add_a_solution_to_a_goal()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();
        $hunt->update(['status' => 'open']);
        $hunt->goals()->save(Goal::make(['title' => 'Dolor conubia mollis']));
        $goal = $hunt->goals->first();

        $response = $this->actingAs($user)
            ->post(route('solution.store', ['goal' => $goal->id]), ['title' => 'Potenti cursus netus']);

        $solution = $hunt->goals->first()->solutions->first();
        $this->assertSame('Potenti cursus netus', $solution->title);
        $response->assertSessionHas('success', 'You successfully added the solution "' . $solution->title . '".');
    }

    public function test_a_participant_can_see_their_submitted_solutions()
    {
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();
        $hunt->goals()->save(Goal::make(['title' => 'Dolor conubia mollis']));
        $hunt->goals->first()->solutions()->save(Solution::make(['title' => 'Potenti cursus netus', 'user_id' => $user->id]));

        $response = $this->actingAs($user)
            ->get(route('hunt.show', ['hunt' => $hunt->id]));

        $response->assertSeeText($hunt->goals->first()->solutions->first()->title);
    }

    public function test_a_participant_can_edit_their_submitted_solutions()
    {
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();
        $hunt->update(['status' => 'open']);
        $hunt->goals()->save(Goal::make(['title' => 'Dolor conubia mollis']));
        $solution = Solution::make(['title' => 'Potenti cursus netus', 'user_id' => $user->id]);
        $hunt->goals->first()->solutions()->save($solution);

        $response = $this->actingAs($user)
            ->patch(route('solution.update', ['goal' => $hunt->goals->first()->id, 'solution' => $solution->id]), ['title' => 'Potenti magnis odio']);

        $this->assertSame('Potenti magnis odio', $solution->fresh()->title);
        $response->assertSessionHas('success', 'You successfully edited the solution "' . $solution->fresh()->title . '".');
    }

    public function test_an_owner_cannot_add_a_solution_to_a_goal_they_own()
    {
        $user = factory(User::class)->states('Owner')->create();
        $hunt = $user->ownedHunts->first();
        $hunt->goals()->save(Goal::make(['title' => 'Dolor conubia mollis']));
        $goal = $hunt->goals->first();

        $response = $this->actingAs($user)
            ->post(route('solution.store', ['goal' => $goal->id]), ['title' => 'Potenti cursus netus']);

        $solution = $hunt->goals->first()->solutions->first();
        $this->assertNull($solution);
        $response->assertStatus(401);
    }

    public function test_a_user_cannot_add_multiple_solutions_to_a_goal()
    {
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();
        $hunt->goals()->save(Goal::make(['title' => 'Dolor conubia mollis']));
        $goal = $hunt->goals->first();
        $hunt->goals->first()->solutions()->save(Solution::make(['title' => 'Potenti cursus netus', 'user_id' => $user->id]));

        $response = $this->actingAs($user)
            ->post(route('solution.store', ['goal' => $goal->id]), ['title' => 'Nibh imperdiet luctus']);

        $this->assertCount(1, $hunt->goals->first()->solutions->all());
        $response->assertStatus(422);
    }

    public function test_a_user_cannot_add_a_solution_to_a_closed_hunt()
    {
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();
        $hunt->goals()->save(Goal::make(['title' => 'Dolor conubia mollis']));
        $goal = $hunt->goals->first();
        $hunt->update(['status' => 'closed']);

        $response = $this->actingAs($user)
            ->post(route('solution.store', ['goal' => $goal->id]), ['title' => 'Nibh imperdiet luctus']);

        $this->assertCount(0, $hunt->goals->first()->solutions->all());
        $response->assertStatus(422);
    }

    public function test_a_user_cannot_edit_a_solution_on_a_closed_hunt()
    {
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();
        $hunt->goals()->save(Goal::make(['title' => 'Dolor conubia mollis']));
        $goal = $hunt->goals->first();
        $solution = Solution::make(['title' => 'Hendrerit habitant ridiculus', 'user_id' => $user->id]);
        $hunt->goals->first()->solutions()->save($solution);
        $hunt->update(['status' => 'closed']);

        $response = $this->actingAs($user)
            ->patch(route('solution.update', ['goal' => $hunt->goals->first()->id, 'solution' => $solution->id]), ['title' => 'Bibendum facilisi mauris']);

        $this->assertSame('Hendrerit habitant ridiculus', $solution->fresh()->title);
        $response->assertStatus(422);
    }

    public function test_an_owner_can_see_the_submitted_solutions()
    {
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();
        $hunt->goals()->save(Goal::make(['title' => 'Dolor conubia mollis']));
        $solutions = ['Potenti cursus netus', 'Sodales sagittis cubilia', 'Bibendum fringilla per'];
        $hunt->goals->first()->solutions()->save(Solution::make(['title' => $solutions[0], 'user_id' => $user->id]));
        $hunt->goals->first()->solutions()->save(Solution::make(['title' => $solutions[1], 'user_id' => $user->id]));
        $hunt->goals->first()->solutions()->save(Solution::make(['title' => $solutions[2], 'user_id' => $user->id]));

        $response = $this->actingAs($hunt->owner)
            ->get(route('hunt.show', ['hunt' => $hunt->id]));

        $response->assertSeeTextInOrder($solutions);
    }
}
