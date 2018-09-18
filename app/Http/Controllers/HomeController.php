<?php

namespace App\Http\Controllers;

use App\Hunt;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $owned_hunts = auth()->check() ? auth()->user()->ownedHunts : collect();
        $other_hunts = $hunts = Hunt::where('owner_id', '!=', auth()->id())->get();

        return view('home', compact('owned_hunts', 'other_hunts'));
    }
}
