<?php

namespace Dungeon\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        if (Auth::guest()) {
            return redirect('login');
        } else {
            return view('interface');
        }
    }
}
