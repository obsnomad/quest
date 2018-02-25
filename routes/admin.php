<?php

Route::get('/', function () {
    return redirect()->route('admin.bookings.index');
});

Route::resource('bookings', 'BookingController');
Route::resource('clients', 'ClientController');
