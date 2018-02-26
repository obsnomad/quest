<?php

Route::get('/', ['as' => 'index', 'uses' => 'HomeController@index']);
Route::get('/schedule', ['as' => 'schedule', 'uses' => 'HomeController@schedule']);
Route::get('/contacts', ['as' => 'contacts', 'uses' => 'HomeController@contacts']);

Auth::routes();
