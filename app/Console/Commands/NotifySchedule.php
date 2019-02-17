<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifySchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notification of today schedule';

    /**
     * Execute the console command.
     * @throws \Throwable
     */
    public function handle()
    {
        try {
            \VKAPI::call('messages.send', [
                'domain' => 'obscurus',
                'message' => view('email.schedule', [
                    'bookings' => Booking::today()
                        ->with('client')
                        ->orderBy('date')
                        ->get(),
                    'date' => Carbon::now()->format('d.m.Y'),
                ])->render(),
            ]);
        } catch (\Exception $e) {
            $this->error('VK message was not send');
        }
    }
}
