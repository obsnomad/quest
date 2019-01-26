НОВАЯ РЕГИСТРАЦИЯ
Квест: {{ $booking->quest->name }}
Дата: {{ $booking->dateFormatted }}
Количество человек: {{ $booking->amount}}
Цена: {{ $booking->price }} р.
@if($client->phoneFormatted)
Номер телефона: {{ $client->phoneFormatted }}
@endif
@if($client->fullName)
Имя: {{ $client->fullName }}
@endif
@if($vkAccountId)
Страница VK: {{ $vkAccountId }}
@endif
