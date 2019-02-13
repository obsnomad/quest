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
                $vkAccountId = !empty($booking->client->vkAccountId)
                    ? "https://vk.com/id{$booking->client->vkAccountId}"
                    : '';
                try {
                    $message = "НОВАЯ РЕГИСТРАЦИЯ
                    
                        Квест: {$booking->quest->name}
                        Дата: {$booking->dateFormatted}
                        Количество человек: {$booking->amount}
                        Цена: {$booking->price} р.";
                    if ($booking->client->phoneFormatted) {
                        $message .= "\nНомер телефона: {$booking->client->phoneFormatted}";
                    }
                    if ($booking->client->fullName) {
                        $message .= "\nИмя: {$booking->client->fullName}";
                    }
                    if ($vkAccountId) {
                        $message .= "\nСтраница VK: {$vkAccountId}";
                    }
                    \VKAPI::call('messages.send', [
                        'domain' => 'obscurus',
                        'message' => $message,
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
