<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function schedule()
    {
        $schedule = Schedule::orderBy('week_day')
            ->orderBy('time')
            ->get()
            ->groupBy('week_day_name')
            ->map(function(Collection $value) {
                return $value->groupBy('price');
            });
        return view('public.index', [
            'schedule' => $schedule,
        ]);
    }
}
