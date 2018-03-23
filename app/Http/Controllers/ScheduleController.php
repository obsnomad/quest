<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Quest;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Request::expectsJson()) {
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
     */
    public function book()
    {
        $data = \Request::validate([
            'phone' => 'required',
            'time' => 'required|date',
            'quest' => 'required|numeric',
        ], [
            'phone.required' => 'Введите номер телефона.',
        ]);
        $quests = Booking::query()
            ->where('quest_id', $data['quest'])
            ->where('time', $data['time']);
        return response()->json([
            'result' => 'Вы успешно забронировали квест. Мы позвоним Вам для подтверждения в ближайшее время.'
        ]);
    }
}
