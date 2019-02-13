<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Booking extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $client;

    /**
     * Create a new message instance.
     *
     * @param $booking
     * @param $client
     */
    public function __construct($booking, $client = null)
    {
        $this->booking = $booking;
        $this->client = $client ?: $booking->client;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_USERNAME'))
            ->to('akatelnikov@yandex.ru')
            ->subject('НОВАЯ РЕГИСТРАЦИЯ')
            ->text('email.booking', [
                'booking' => $this->booking,
                'client' => $this->client,
                'vkAccountId' => $this->client->vkAccountId ? "https://vk.com/id{$this->client->vkAccountId}" : '',
            ]);
    }
}
