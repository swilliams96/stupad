<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function() {
    return view('home');
});

Route::get('/results', function() {
    // TODO: implement the search results page
    return redirect('/');
});

Route::get('/login', function() {
    // TODO: implement login page
    return redirect('/');
});

Route::get('/register', function() {
    // TODO: implement login page
    return redirect('/');
});



/****************************/
/* TODO: CREATE BELOW PAGES */
/****************************/

Route::get('/landlord', function() {
    return redirect('/');
});

Route::get('/login', function() {
    return redirect('/');
});
