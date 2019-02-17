<?php

namespace App\Console\Commands;

use App\Mail\Booking as MailBooking;
use App\Models\Booking;
use Illuminate\Console\Command;

class NotifyBooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:booking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notification of booking';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Booking::new()
            ->with('client')
            ->get()
            ->map(function (Booking $booking) {
                try {
                    \VKAPI::call('messages.send', [
                        'domain' => 'obscurus',
                        'message' => view('email.booking', [
                            'booking' => $booking,
                        ])->render(),
                    ]);
                } catch (\Exception $e) {
                    $this->error('VK message was not send');
                }
                try {
                    \Mail::send(new MailBooking($booking));
                } catch (\Exception $e) {
                    $this->error('Email was not send');
                }
                $booking->notified = 1;
                $booking->save();
            });
    }
}
