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

        $quests = [
            (object)[
                'name' => 'Лечебница',
                'image' => '/images/slides-main-1.jpg',
                'level' => 'Простой',
                'time' => '60 минут',
                'players' => '2-5 игроков',
                'money' => 'от 1600 рублей',
            ],
            (object)[
                'name' => 'Фантом',
                'image' => '/images/slides-main-1.jpg',
                'level' => 'Сложный',
                'time' => '90 минут',
                'players' => '2-4 игрока',
                'money' => 'от 2000 рублей',
                'special' => 'С закрытыми глазами',
                'specialStyle' => 'red',
            ],
            (object)[
                'name' => 'Книга мёртвых',
                'image' => '/images/slides-main-1.jpg',
                'level' => 'Несложный',
                'time' => '60 минут',
                'players' => '2-5 игроков',
                'money' => 'от 1600 рублей',
                'special' => 'Страшный',
                'specialStyle' => 'dark',
            ],
        ];

        return view('public.index', [
            'schedule' => $schedule,
            'quests' => $quests,
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
