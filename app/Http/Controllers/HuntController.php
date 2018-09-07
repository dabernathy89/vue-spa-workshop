<?php

namespace App\Http\Controllers;

use App\Hunt;
use App\User;
use Illuminate\Http\Request;

class HuntController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hunts = Hunt::where('owner_id', '!=', auth()->id())->get();
        return view('hunt.index', compact('hunts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('hunt.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => 'required|max:255',
        ]);

        $hunt = Hunt::create(array_merge([
            'owner_id' => auth()->user()->id,
            'status' => 'open',
        ], $input));

        return redirect()->route('home')->with('success', 'You successfully created the Scavenger Hunt "' . $hunt->name . '".');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Hunt  $hunt
     * @return \Illuminate\Http\Response
     */
    public function show(Hunt $hunt)
    {
        return view('hunt.show', compact('hunt'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Hunt  $hunt
     * @return \Illuminate\Http\Response
     */
    public function edit(Hunt $hunt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Hunt  $hunt
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Hunt $hunt, Request $request)
    {
        abort_if($hunt->owner->id !== auth()->id() || $hunt->isClosed, 403);
        abort_if(!empty($input['winner_id']) && !$hunt->participants->pluck('id')->contains($input['winner_id']), 422);

        $input = $request->validate([
            'name' => 'sometimes|required|max:255',
            'status' => 'sometimes|in:open,closed',
            'winner_id' => 'integer|exists:users,id',
        ]);

        $hunt->name = $input['name'] ?? $hunt->name;
        $hunt->status = $input['status'] ?? $hunt->status;
        $hunt->winner_id = $input['winner_id'] ?? $hunt->winner_id;

        if ($hunt->winner_id) {
            $hunt->status = 'closed';
        }

        $message = 'You have successfully updated the Scavenger Hunt "' . $hunt->name . '".';
        if ($hunt->isDirty('winner_id') && $hunt->winner_id) {
            $message = 'You have successfully chosen a winner for the Scavenger Hunt "' . $hunt->name . '".';
        } elseif ($hunt->isDirty('status') && $hunt->isClosed) {
            $message = 'You have successfully closed the Scavenger Hunt "' . $hunt->name . '".';
        }

        $hunt->save();

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Hunt  $hunt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hunt $hunt)
    {
        abort_if($hunt->owner->id !== auth()->id(), 403, 'You do not have permission to delete that Scavenger Hunt.');

        $hunt->delete();
        return redirect()->route('home')->with('success', 'Scavenger Hunt "' . $hunt->name . '" was successfully deleted.');
    }

    /**
     * Remove a user from a scavenger hunt
     *
     * @param  \App\Hunt  $hunt
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function removeUser(Hunt $hunt, User $user)
    {
        abort_if(
            auth()->id() !== $user->id || !$hunt->participants->pluck('id')->contains($user->id),
            403,
            'You do not have permission to remove the user from the Scavenger Hunt.'
        );

        $hunt->participants()->detach($user);
        return redirect()->back()->with('success', 'You successfully left the Scavenger Hunt "' . $hunt->name . '".');
    }

    /**
     * Adds a user to a scavenger hunt
     *
     * @param  \App\Hunt  $hunt
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function addUser(Hunt $hunt, User $user)
    {
        abort_if(auth()->id() !== $user->id || $hunt->owner->id === $user->id, 403);
        abort_if($hunt->isClosed, 422);

        $hunt->participants()->attach($user);
        return redirect()->route('hunt.show', ['hunt' => $hunt->id])->with('success', 'You successfully joined the Scavenger Hunt "' . $hunt->name . '".');
    }
}
