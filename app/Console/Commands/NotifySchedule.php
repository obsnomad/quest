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
     */
    public function handle()
    {
        $messages = Booking::today()
            ->with('client')
            ->get()
            ->map(function (Booking $booking) {
                $vkAccountId = !empty($booking->client->vkAccountId)
                    ? "https://vk.com/id{$booking->client->vkAccountId}"
                    : '';
                $message = "
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
                return $message;
            });
        try {
            $date = Carbon::now()->format('d.m.Y');
            \VKAPI::call('messages.send', [
                'domain' => 'obscurus',
                'message' => $messages->count() > 0
                    ? "БРОНИ НА {$date}\n" . $messages->implode("\n")
                    : "ОТЧЕТ НА {$date} - НА СЕГОДНЯ БРОНЕЙ ПОКА НЕТ",
            ]);
        } catch (\Exception $e) {
            $this->error('VK message was not send');
        }
    }
}
