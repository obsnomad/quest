<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\BookingHistory;

class BookingObserver
{
    /**
     * Listen to the Booking saving event.
     *
     * @param Booking $booking
     * @return void
     */
    public function saving(Booking $booking)
    {
        $booking->updatedBy = \Auth::user()->id;
    }

    /**
     * Listen to the Booking saved event.
     *
     * @param Booking $booking
     * @return void
     */
    public function saved(Booking $booking)
    {
        BookingHistory::create([
            'booking_id' => $booking->id,
            'status_id' => $booking->statusId,
            'client_id' => $booking->clientId,
            'date' => $booking->date,
            'price' => $booking->price,
            'amount' => $booking->amount,
            'comment' => $booking->comment,
            'created_by' => $booking->updatedBy,
        ]);
    }
}