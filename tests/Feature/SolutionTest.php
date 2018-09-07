<?php
// phpcs:disable PSR1.Methods.CamelCapsMethodName

namespace Tests\Feature;

use App\User;
use App\Hunt;
use App\Goal;
use App\Solution;
use Tests\TestCase;
use Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class SolutionTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_participant_can_add_a_solution_to_a_goal()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('solution.jpg');
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();
        $hunt->update(['status' => 'open']);
        $hunt->goals()->save(Goal::make(['title' => 'Dolor conubia mollis']));
        $goal = $hunt->goals->first();

        $response = $this->actingAs($user)
            ->post(route('solution.store', ['goal' => $goal->id]), ['image' => $file]);

        $solution = $hunt->goals->first()->solutions->first();
        $this->assertContains($file->hashName(), $solution->image);
        // dd(Storage::disk('public')->allFiles());
        Storage::disk('public')->assertExists($file->hashName());
        $response->assertSessionHas('success', 'You successfully added a solution.');
    }

    public function test_a_participant_can_see_their_submitted_solutions()
    {
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();
        $hunt->goals()->save(Goal::make(['title' => 'Dolor conubia mollis']));
        $solution = Solution::make(['image' => '/storage/foo.jpg', 'user_id' => $user->id]);
        $hunt->goals->first()->solutions()->save($solution);

        $response = $this->actingAs($user)
            ->get(route('hunt.show', ['hunt' => $hunt->id]));

        $response->assertSee('src="' . asset($solution->fresh()->image) . '"');
    }

    public function test_a_participant_can_edit_their_submitted_solutions()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('solution.jpg');
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();
        $hunt->update(['status' => 'open']);
        $hunt->goals()->save(Goal::make(['title' => 'Dolor conubia mollis']));
        $solution = Solution::make(['image' => '/storage/foo.jpg', 'user_id' => $user->id]);
        $hunt->goals->first()->solutions()->save($solution);

        $response = $this->actingAs($user)
            ->patch(route('solution.update', ['goal' => $hunt->goals->first()->id, 'solution' => $solution->id]), ['image' => $file]);

        $this->assertContains($file->hashName(), $solution->fresh()->image);
        Storage::disk('public')->assertExists($file->hashName());
        $response->assertSessionHas('success', 'You successfully updated a solution.');
    }

    public function test_an_owner_cannot_add_a_solution_to_a_goal_they_own()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('solution.jpg');
        $user = factory(User::class)->states('Owner')->create();
        $hunt = $user->ownedHunts->first();
        $hunt->goals()->save(Goal::make(['title' => 'Dolor conubia mollis']));
        $goal = $hunt->goals->first();

        $response = $this->actingAs($user)
            ->post(route('solution.store', ['goal' => $goal->id]), ['image' => $file]);

        $solution = $hunt->goals->first()->solutions->first();
        $this->assertNull($solution);
        Storage::disk('public')->assertMissing($file->hashName());
        $response->assertStatus(403);
    }

    public function test_a_user_cannot_add_multiple_solutions_to_a_goal()
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->image('solution.jpg');
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();
        $hunt->goals()->save(Goal::make(['title' => 'Dolor conubia mollis']));
        $goal = $hunt->goals->first();
        $hunt->goals->first()->solutions()->save(Solution::make(['image' => '/storage/foo.jpg', 'user_id' => $user->id]));

        $response = $this->actingAs($user)
            ->post(route('solution.store', ['goal' => $goal->id]), ['image' => $file]);

        $this->assertCount(1, $hunt->goals->first()->solutions->all());
        Storage::disk('public')->assertMissing($file->hashName());
        $response->assertStatus(422);
    }

    public function test_a_user_cannot_add_a_solution_to_a_closed_hunt()
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->image('solution.jpg');
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();
        $hunt->goals()->save(Goal::make(['title' => 'Dolor conubia mollis']));
        $goal = $hunt->goals->first();
        $hunt->update(['status' => 'closed']);

        $response = $this->actingAs($user)
            ->post(route('solution.store', ['goal' => $goal->id]), ['image' => $file]);

        $this->assertCount(0, $hunt->goals->first()->solutions->all());
        Storage::disk('public')->assertMissing($file->hashName());
        $response->assertStatus(422);
    }

    public function test_a_user_cannot_edit_a_solution_on_a_closed_hunt()
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->image('solution.jpg');
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();
        $hunt->goals()->save(Goal::make(['title' => 'Dolor conubia mollis']));
        $goal = $hunt->goals->first();
        $solution = Solution::make(['image' => '/storage/foo.jpg', 'user_id' => $user->id]);
        $hunt->goals->first()->solutions()->save($solution);
        $hunt->update(['status' => 'closed']);

        $response = $this->actingAs($user)
            ->patch(route('solution.update', ['goal' => $hunt->goals->first()->id, 'solution' => $solution->id]), ['image' => $file]);

        $this->assertSame('/storage/foo.jpg', $solution->fresh()->image);
        Storage::disk('public')->assertMissing($file->hashName());
        $response->assertStatus(422);
    }

    public function test_an_owner_can_see_the_submitted_solutions()
    {
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();
        $hunt->goals()->save(Goal::make(['title' => 'Dolor conubia mollis']));
        $solutions = ['/storage/potenti.jpg', '/storage/sodales.jpg', '/storage/bibendum.jpg'];
        $hunt->goals->first()->solutions()->save(Solution::make(['image' => $solutions[0], 'user_id' => $user->id]));
        $hunt->goals->first()->solutions()->save(Solution::make(['image' => $solutions[1], 'user_id' => $user->id]));
        $hunt->goals->first()->solutions()->save(Solution::make(['image' => $solutions[2], 'user_id' => $user->id]));

        $response = $this->actingAs($hunt->owner)
            ->get(route('hunt.show', ['hunt' => $hunt->id]));

        $solutions = collect($solutions)->map(function ($solution) {
            return 'src="' . asset($solution) . '"';
        })->all();

        $response->assertSeeInOrder($solutions);
    }
}
