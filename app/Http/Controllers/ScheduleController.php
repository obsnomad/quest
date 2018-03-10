<?php

namespace App\Http\Controllers;

use App\Models\Quest;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quests = Quest::query()
            ->with('location')
            ->get()
            ->groupBy('quest_location_id');
        $schedule = Schedule::getNextDays();
        return view('public.schedule', [
            'quests' => $quests,
            'schedule' => $schedule,
        ]);
    }
}
