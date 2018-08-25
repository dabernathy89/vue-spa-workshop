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
        $hunts = Hunt::all();
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
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Hunt  $hunt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hunt $hunt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Hunt  $hunt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hunt $hunt)
    {
        abort_if($hunt->owner->id !== auth()->id(), 401, 'You do not have permission to delete that Scavenger Hunt.');

        $hunt->delete();
        return redirect()->route('home')->with('success', 'Scavenger Hunt "' . $hunt->name . '" was successfully deleted.');
    }

    /**
     * Remove a user from a scaventer hunt
     *
     * @param  \App\Hunt  $hunt
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function removeUser(Hunt $hunt, User $user)
    {
        abort_if(
            auth()->id() !== $user->id || !$hunt->participants->pluck('id')->contains($user->id),
            401,
            'You do not have permission to remove the user from the Scavenger Hunt.'
        );

        $hunt->participants()->detach($user);
        return redirect()->back()->with('success', 'You successfully left the Scavenger Hunt "' . $hunt->name . '".');
    }

    /**
     * Adds a user to a scaventer hunt
     *
     * @param  \App\Hunt  $hunt
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function addUser(Hunt $hunt, User $user)
    {
        abort_if(auth()->id() !== $user->id, 401, 'You do not have permission to add this user to the Scavenger Hunt.');

        $hunt->participants()->attach($user);
        return redirect()->back()->with('success', 'You successfully joined the Scavenger Hunt "' . $hunt->name . '".');
    }
}
