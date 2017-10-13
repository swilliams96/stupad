<?php

// HOME CONTROLLER
Route::get('/', 'HomeController@index')->name('home');
Route::get('/landlord', 'HomeController@landlord')->name('landlord');

// AUTH ROUTES
Auth::routes();

// SEARCH CONTROLLER
Route::get('/search', 'SearchController@search')->name('search');
Route::post('/results', 'SearchController@results')->name('results');
Route::get('/results/{slug}', 'SearchController@showresults');

// DASHBOARD CONTROLLER
Route::get('/dashboard/profile', 'DashboardController@profile')->name('profile');
Route::post('/dashboard/profile', 'DashboardController@updateprofile');
Route::post('/dashboard/updatepassword', 'DashboardController@updatepassword');

Route::get('/dashboard/messages', 'DashboardController@messages')->name('messages');
Route::get('/dashboard/messages/{id}', 'DashboardController@viewmessage');
Route::post('/dashboard/messages/{id}', 'DashboardController@sendmessage');

Route::get('/dashboard/listings', 'DashboardController@mylistings')->name('mylistings');
Route::get('/dashboard/listings/new', 'DashboardController@newlisting')->name('newlisting');
Route::get('/dashboard/listings/edit/{id}', 'DashboardController@editlisting');
Route::get('/dashboard/listings/delete/{id}', 'DashboardController@deletelisting');

Route::get('/dashboard/saved', 'DashboardController@savedlistings')->name('savedlistings');

// LISTING CONTROLLER
Route::get('/listings/{listing_id}/{slug?}', 'ListingController@show');
Route::resource('listings', 'ListingController');

Route::post('/listings/{id}/activate', 'ListingController@activate');
Route::post('/listings/{id}/deactivate', 'ListingController@deactivate');
Route::post('/listings/{id}/renew', 'ListingController@renew');

Route::post('/listings/{id}/save', 'ListingController@save');
Route::post('/listings/{id}/unsave', 'ListingController@unsave');

// TODO: UNIMPLEMENTED PAGES
Route::get('/terms', 'HomeController@unimplemented')->name('termsandconditions');
Route::get('/privacy', 'HomeController@unimplemented')->name('privacypolicy');