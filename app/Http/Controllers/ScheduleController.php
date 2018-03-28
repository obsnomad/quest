<?php

namespace App\Http\Controllers;

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
        return view('public.schedule');
    }

    /**
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function book()
    {
        $data = \Request::validate([
            'phone' => 'required|regex:/\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}/',
            'time' => 'required|date',
            'quest' => 'required|numeric',
        ], [
            'phone.required' => 'Введите номер телефона.',
            'phone.regex' => 'Введите корректный номер телефона.',
        ]);
        $scheduleItem = Schedule::getByDate('2018-04-01 10:00', 1);
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
        $client = Client::firstOrCreate([
            'phone' => Client::cleanPhone($data['phone']),
        ]);
        Booking::create([
            'quest_id' => $data['quest'],
            'client_id' => $client->id,
            'date' => $data['time'],
            'price' => $scheduleItem->price,
            'status_id' => 1,
        ]);
        \DB::commit();
        return response()->json([
            'result' => 'Вы успешно забронировали квест. Мы позвоним Вам в ближайшее время для подтверждения.'
        ]);
    }
}
