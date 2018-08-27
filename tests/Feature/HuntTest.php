<?php
// phpcs:disable PSR1.Methods.CamelCapsMethodName

namespace Tests\Feature;

use App\User;
use App\Hunt;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HuntTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_visitor_cannot_see_the_create_hunt_form()
    {
        $response = $this->get(route('hunt.create'));

        $response->assertRedirect('/login');
    }

    public function test_a_user_can_see_the_create_hunt_form()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get(route('hunt.create'));

        $response->assertSee('Create A Scavenger Hunt');
    }

    public function test_a_user_cannot_create_a_hunt_without_a_name()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->post(route('hunt.store'), []);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_a_user_can_create_a_hunt()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)
            ->post(route('hunt.store'), [
                'name' => 'Test Name',
            ]);

        $hunt = Hunt::where([
            'name' => 'Test Name',
            'owner_id' => $user->id,
        ])->first();
        $this->assertNotNull($hunt);
        $response->assertSessionHas('success', 'You successfully created the Scavenger Hunt "' . $hunt->name . '".');
    }

    public function test_a_user_can_delete_a_hunt_they_own()
    {
        $user = factory(User::class)->states('Owner')->create();
        $hunt = $user->ownedHunts->first();

        $response = $this->actingAs($user)
            ->delete(route('hunt.delete', ['hunt' => $hunt->id]));

        $this->assertNull(Hunt::find($hunt->id));
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Scavenger Hunt "' . $hunt->name . '" was successfully deleted.');
    }

    public function test_a_user_can_not_delete_a_hunt_they_do_not_own()
    {
        $user = factory(User::class)->states('Owner')->create();
        $user2 = factory(User::class)->states('Owner')->create();
        $hunt = $user2->ownedHunts->first();

        $response = $this->actingAs($user)
            ->delete(route('hunt.delete', ['hunt' => $hunt->id]));

        $this->assertNotNull(Hunt::find($hunt->id));
        $response->assertStatus(401);
    }

    public function test_a_user_can_view_hunts_to_join()
    {
        $user = factory(User::class)->states('Participant')->create();
        $response = $this->actingAs($user)->get(route('hunt.index'));

        $response->assertSeeTextInOrder(Hunt::all()->pluck('name')->all());
    }

    public function test_a_user_cannot_view_owned_hunts_to_join()
    {
        factory(User::class)->states('Participant')->create();
        $owner = factory(User::class)->states('Owner')->create();
        $response = $this->actingAs($owner)->get(route('hunt.index'));

        $response->assertSeeTextInOrder(Hunt::where('owner_id', '!=', $owner->id)->get()->pluck('name')->all());
    }

    public function test_a_user_can_join_a_hunt()
    {
        $user = factory(User::class)->create();
        $hunt = factory(User::class)->states('Owner')->create()->ownedHunts->first();

        $response = $this->actingAs($user)
            ->post(route('hunt.add_user', ['hunt' => $hunt->id, 'user' => $user->id]));

        $this->assertNotNull(
            Hunt::whereId($hunt->id)
                ->whereHas('participants', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->first()
        );
        $response->assertSessionHas('success', 'You successfully joined the Scavenger Hunt "' . $hunt->name . '".');
    }

    public function test_a_user_cannot_join_a_hunt_they_own()
    {
        $user = factory(User::class)->states('Owner')->create();
        $hunt = $user->ownedHunts->first();

        $response = $this->actingAs($user)
            ->post(route('hunt.add_user', ['hunt' => $hunt->id, 'user' => $user->id]));

        $this->assertNull(
            Hunt::whereId($hunt->id)
                ->whereHas('participants', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->first()
        );
        $response->assertStatus(403);
    }

    public function test_a_user_can_leave_a_hunt()
    {
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();

        $response = $this->actingAs($user)
            ->delete(route('hunt.remove_user', ['hunt' => $hunt->id, 'user' => $user->id]));

        $this->assertNull(
            Hunt::whereId($hunt->id)
                ->whereHas('participants', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->first()
        );
        $response->assertSessionHas('success', 'You successfully left the Scavenger Hunt "' . $hunt->name . '".');
    }

    public function test_a_user_can_see_a_hunts_detail_page()
    {
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();

        $response = $this->actingAs($user)->get(route('hunt.show', ['hunt' => $hunt->id]));

        $response->assertSeeText($hunt->name);
        $response->assertSeeText($hunt->owner->name);
        $response->assertSeeTextInOrder($hunt->participants->pluck('name')->all());
    }

    public function test_an_owner_can_end_a_hunt()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->states('Owner')->create();
        $hunt = $user->ownedHunts->first();
        $hunt->status = 'open';
        $hunt->save();

        $response = $this->actingAs($user)
            ->patch(
                route('hunt.update', ['hunt' => $hunt->id]),
                ['status' => 'closed']
            );

        $this->assertSame('closed', $hunt->fresh()->status);
        $response->assertSessionHas('success', 'You have successfully closed the Scavenger Hunt "' . $hunt->name . '".');
    }

    public function test_a_participant_cannot_end_a_hunt()
    {
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();
        $hunt->status = 'open';
        $hunt->save();

        $response = $this->actingAs($user)
            ->patch(
                route('hunt.update', ['hunt' => $hunt->id]),
                ['status' => 'closed']
            );

        $this->assertSame('open', $hunt->fresh()->status);
        $response->assertStatus(401);
    }

    public function test_an_owner_can_choose_a_winner()
    {
        $user = factory(User::class)->states('Participant')->create();
        $hunt = $user->hunts->first();

        $response = $this->actingAs($hunt->owner)
            ->patch(
                route('hunt.update', ['hunt' => $hunt->id]),
                ['winner_id' => $user->id]
            );

        $this->assertSame('closed', $hunt->fresh()->status);
        $this->assertSame($user->id, $hunt->fresh()->winner_id);
        $response->assertSessionHas('success', 'You have successfully chosen a winner for the Scavenger Hunt "' . $hunt->name . '".');
    }

    // TODO tests
    // a user cannot delete or add goals to a closed Hunt
    // a user cannot add or edit solutions to a closed Hunt
}
