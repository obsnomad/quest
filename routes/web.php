<?php

Route::get('/', ['as' => 'index', 'uses' => 'HomeController@index']);
Route::get('/schedule', ['as' => 'schedule', 'uses' => 'ScheduleController@index']);
Route::get('/schedule/{slug}', ['as' => 'schedule.show', 'uses' => 'ScheduleController@show']);
Route::get('/quests', ['as' => 'quests', 'uses' => 'QuestController@index']);
Route::get('/quests/{slug}', ['as' => 'quests.show', 'uses' => 'QuestController@show']);
Route::get('/gift', ['as' => 'gift', 'uses' => 'HomeController@gift']);

Auth::routes();
