<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
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

    /**
     * @return \Illuminate\Http\Response
     */
    public function matrix()
    {
        $schedule = Schedule::orderBy('week_day')
            ->orderBy('time')
            ->get()
            ->groupBy('week_day_name')
            ->map(function(Collection $value) {
                return $value->groupBy('price');
            });
        return view('public.matrix', [
            'schedule' => $schedule,
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
            ->map(function(Collection $value) {
                return $value->groupBy('price');
            });
        return view('public.index', [
            'schedule' => $schedule,
        ]);
    }
}
