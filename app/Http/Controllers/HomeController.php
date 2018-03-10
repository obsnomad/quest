<?php

namespace App\Http\Controllers;

use App\Models\Quest;
use App\Models\QuestLocation;
use App\Models\Schedule;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quests = Quest::active()
            ->get();
        $locations = QuestLocation::query()
            ->get();

        return view('public.index', [
            'quests' => $quests,
            'locations' => $locations,
        ]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function contacts()
    {
        $schedule = Schedule::orderBy('week_day')
            ->orderBy('time')
            ->get()
            ->groupBy('week_day_name')
            ->map(function (Collection $value) {
                return $value->groupBy('price');
            });
        return view('public.index', [
            'schedule' => $schedule,
        ]);
    }
}
