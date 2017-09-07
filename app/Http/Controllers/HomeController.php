<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SearchController;

class HomeController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index() {
        return view('home')->with('all_locations', SearchController::getlocations());
    }

    public function landlord() {
        return redirect('/');
        // TODO: when implemented, change to:
        // return view('landlord');
    }

    public function unimplemented() {
        return redirect('/');
    }
}
