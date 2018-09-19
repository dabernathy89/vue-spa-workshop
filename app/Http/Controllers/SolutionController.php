<?php

namespace App\Http\Controllers;

use App\Solution;
use App\Goal;
use Storage;
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
        abort_if(!$goal->hunt->participants->pluck('id')->contains(auth()->id()), 403);
        abort_if($goal->hunt->status === 'closed' || $goal->solutions->pluck('user_id')->contains(auth()->id()), 422);

        $input = $request->validate([
            'image' => 'required|image|max:2000',
        ]);

        $path = $request->file('image')->store(null, 'public');

        $solution = Solution::make([
            'user_id' => auth()->id(),
            'image' => $path,
        ]);
        $goal->solutions()->save($solution);

        return response()->json([
            'successMessage' => 'You successfully added a solution.',
            'solution' => $solution
        ]);
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
        abort_if($solution->user_id !== auth()->id(), 403);
        abort_if($goal->hunt->status === 'closed', 422);

        $input = $request->validate([
            'image' => 'required|image|max:2000',
        ]);

        Storage::disk('public')->delete($solution->image);

        $solution->update([
            'image' => $request->file('image')->store(null, 'public')
        ]);

        return response()->json([
            'successMessage' => 'You successfully updated a solution.',
            'solution' => $solution
        ]);
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
