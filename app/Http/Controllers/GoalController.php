<?php

namespace App\Http\Controllers;

use App\Goal;
use App\Hunt;
use Validator;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Hunt  $hunt
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Hunt $hunt, Request $request)
    {
        abort_if($hunt->owner->id !== auth()->id(), 403);
        abort_if($hunt->status === 'closed', 422);

        $input = $request->validate([
            'title' => 'required|max:255',
        ]);

        if ($hunt->goals()->whereTitle($input['title'])->first() ?? false) {
            return redirect()->back()->withErrors(['title' => 'There is already a goal with that title']);
        }

        $goal = Goal::create(array_merge([
            'hunt_id' => $hunt->id,
        ], $input));

        return redirect()->back()->with('success', 'You successfully added the goal "' . $goal->title . '".');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Goal  $goal
     * @return \Illuminate\Http\Response
     */
    public function show(Goal $goal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Goal  $goal
     * @return \Illuminate\Http\Response
     */
    public function edit(Goal $goal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Goal  $goal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Goal $goal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Hunt  $hunt
     * @param  \App\Goal  $goal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hunt $hunt, Goal $goal)
    {
        abort_if($hunt->owner->id !== auth()->id(), 403);
        abort_if($hunt->status === 'closed', 422);

        $goal->delete();

        return redirect()->back()->with('success', 'You successfully deleted the goal "' . $goal->title . '".');
    }
}
