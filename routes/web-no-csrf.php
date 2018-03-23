<?php

Route::post('/schedule', ['as' => 'schedule.book', 'uses' => 'ScheduleController@book']);
