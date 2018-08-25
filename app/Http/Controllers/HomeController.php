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
        $participating_hunts = auth()->check() ? auth()->user()->hunts : collect();

        return view('home', compact('owned_hunts', 'participating_hunts'));
    }
}
