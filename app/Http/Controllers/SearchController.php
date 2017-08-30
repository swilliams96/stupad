<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{

    public function __construct() {}

    public function search() {
        return view('search');
    }

    public function results() {
        return 'results';
    }
}
