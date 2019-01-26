<?php

Route::post('/schedule', ['as' => 'schedule.book', 'uses' => 'ScheduleController@book']);
Route::post('/gift', ['as' => 'gift.send', 'uses' => 'HomeController@giftSend']);
