@php
/** @var \App\Models\Booking $booking */
@endphp
НОВАЯ РЕГИСТРАЦИЯ

Квест: {{ $booking->quest->name }}
Дата: {{ $booking->dateFormatted }}
Количество человек: {{ $booking->amount}}
Цена: {{ $booking->price }} р.
@if($booking->client->phoneFormatted)
Номер телефона: {{ $booking->client->phoneFormatted }}
@endif
@if($booking->client->vkAccountId)
Страница VK: https://vk.com/id{{ $booking->client->vkAccountId }}
@endif
@if($booking->client->fullName)
Имя: {{ $booking->client->fullName }}
@endif
