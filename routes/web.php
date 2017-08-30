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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/search', 'SearchController@search')->name('search');
Route::get('/results', 'SearchController@results')->name('results');

Route::get('/landlord', 'HomeController@landlord')->name('landlord');

// TODO: implement below pages
Route::get('/results', 'SearchController@results')->name('results');
Route::get('/profile', 'HomeController@unimplemented')->name('profile');
Route::get('/terms', 'HomeController@unimplemented')->name('termsandconditions');
Route::get('/privacy', 'HomeController@unimplemented')->name('privacypolicy');