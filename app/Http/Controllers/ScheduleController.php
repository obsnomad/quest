<?php

namespace App\Http\Controllers;

use App\Mail\Booking as MailBooking;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Quest;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Request::expectsJson()) {
            $quests = Quest::query()
                ->with('location')
                ->get()
                ->groupBy('quest_location_id')
                ->values();
            $schedule = Schedule::getNextDays();
            return response()->json([
                'quests' => $quests,
                'schedule' => $schedule,
            ]);
        }
        return view('public.schedule', [
            'vkAccountId' => strpos(\Request::input('api_url'), 'vk.com') ? (\Request::input('user_id') ?: \Request::input('viewer_id')) : null,
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (\Request::expectsJson()) {
            $quest = Quest::active()
                ->where('id', $id)
                ->first();
            if (!$quest) {
                abort(404);
            }
            $schedule = Schedule::getNextDays($quest->id);
            return response()->json([
                'quest' => $quest,
                'schedule' => $schedule,
            ]);
        }
        return redirect()->route('schedule');
    }

    /**
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function book()
    {
        $data = \Request::validate([
            'phone' => 'required_without:vkAccountId|nullable|regex:/\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}/',
            'amount' => 'required|numeric|min:4',
            'time' => 'required|date',
            'quest' => 'required|numeric',
            'vkAccountId' => 'required_without:phone',
        ], [
            'phone.required_without' => 'Введите номер телефона.',
            'phone.regex' => 'Введите корректный номер телефона.',
            'vkAccountId.required_without' => 'Не обнаружен идентификатор пользователя VK.',
        ]);
        $scheduleItem = Schedule::getByDate($data['time'], $data['quest']);
        if (!$scheduleItem) {
            return response()->json([
                'message' => 'Это время нельзя забронировать. Попробуйте повторить попытку.'
            ], 403);
        }
        if ($scheduleItem->booked) {
            return response()->json([
                'message' => 'Это время уже кто-то забронировал. Пожалуйста, попробуйте выбрать другое время.'
            ], 403);
        }
        \DB::beginTransaction();
        /**
         * @var Client $client
         */
        $clientData = !empty($data['vkAccountId'])
            ? ['vk_account_id' => $data['vkAccountId']]
            : ['phone' => Client::cleanPhone($data['phone'])];
        $client = Client::firstOrCreate($clientData);
        $booking = Booking::create([
            'quest_id' => $data['quest'],
            'client_id' => $client->id,
            'date' => $data['time'],
            'price' => $scheduleItem->price + 300 * ($data['amount'] - 4),
            'amount' => $data['amount'],
            'status_id' => 1,
        ]);
        $vkAccountId = $client->vkAccountId ? "https://vk.com/id{$client->vkAccountId}" : '';
        try {
            $message = "НОВАЯ РЕГИСТРАЦИЯ
            Квест: {$booking->quest->name}
            Дата: {$booking->dateFormatted}
            Количество человек: {$booking->amount}
            Цена: {$booking->price} р.";
            if($client->phoneFormatted) {
                $message .= "\nНомер телефона: {$client->phoneFormatted}";
            }
            if($client->fullName) {
                $message .= "\nИмя: {$client->fullName}";
            }
            if($vkAccountId) {
                $message .= "\nСтраница VK: {$vkAccountId}";
            }
            \VKAPI::call('messages.send', [
                'domain' => 'obscurus',
                'message' => $message,
            ]);
        } catch (\Exception $e) {

        }
        try {
            \Mail::send(new MailBooking($booking, $client));
        } catch (\Exception $e) {

        }
        \DB::commit();
        return response()->json([
            'result' => 'Вы успешно забронировали квест. Мы позвоним Вам в ближайшее время для подтверждения.'
        ]);
    }
}
