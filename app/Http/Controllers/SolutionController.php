<?php

namespace App\Http\Controllers;

use App\Solution;
use App\Goal;
use Illuminate\Http\Request;

class SolutionController extends Controller
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
     * @param  \App\Goal  $goal
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Goal $goal, Request $request)
    {
        abort_if(!$goal->hunt->participants->pluck('id')->contains(auth()->id()), 401);
        abort_if($goal->hunt->status === 'closed' || $goal->solutions->pluck('user_id')->contains(auth()->id()), 422);

        $input = $request->validate([
            'title' => 'required|max:255',
        ]);

        $solution = Solution::make(array_merge([
            'user_id' => auth()->id()
        ], $input));
        $goal->solutions()->save($solution);

        return redirect()->back()->with('success', 'You successfully added the solution "' . $solution->title . '".');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Solution  $solution
     * @return \Illuminate\Http\Response
     */
    public function show(Solution $solution)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Solution  $solution
     * @return \Illuminate\Http\Response
     */
    public function edit(Solution $solution)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Goal  $goal
     * @param  \App\Solution  $solution
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Goal $goal, Solution $solution, Request $request)
    {
        abort_if($solution->user_id !== auth()->id(), 401);
        abort_if($goal->hunt->status === 'closed', 422);

        $input = $request->validate([
            'title' => 'required|max:255',
        ]);

        $solution->title = $input['title'];
        $solution->save();

        return redirect()->back()->with('success', 'You successfully edited the solution "' . $solution->title . '".');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Solution  $solution
     * @return \Illuminate\Http\Response
     */
    public function destroy(Solution $solution)
    {
        //
    }
}
