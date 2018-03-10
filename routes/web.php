<?php

Route::get('/', ['as' => 'index', 'uses' => 'HomeController@index']);
Route::get('/schedule', ['as' => 'schedule', 'uses' => 'HomeController@schedule']);
Route::get('/quests', ['as' => 'quests', 'uses' => 'QuestController@index']);
Route::get('/quests/{slug}', ['as' => 'quests.show', 'uses' => 'QuestController@show']);
Route::get('/contacts', ['as' => 'contacts', 'uses' => 'HomeController@contacts']);

Auth::routes();
