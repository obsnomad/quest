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
        $quests = [
            (object)[
                'name' => 'Ночь в музее',
                'image' => '/images/slides-main-4.jpg',
                'level' => 'Простой',
                'time' => '60 минут',
                'players' => '2-5 игроков',
                'money' => 'от 1600 рублей',
                'active' => true,
            ],
            (object)[
                'name' => 'Психбольница',
                'image' => '/images/slides-main-2.jpg',
                'level' => 'Несложный',
                'time' => '60 минут',
                'players' => '2-5 игроков',
                'money' => 'от 1600 рублей',
                'active' => false,
            ],
            (object)[
                'name' => 'Фантом',
                'image' => '/images/slides-main-5.jpg',
                'level' => 'Сложный',
                'time' => '90 минут',
                'players' => '2-4 игрока',
                'money' => 'от 2000 рублей',
                'special' => 'С закрытыми глазами',
                'specialStyle' => 'red',
                'active' => false,
            ],
            (object)[
                'name' => 'Секретные материалы',
                'image' => '/images/slides-main-6.jpg',
                'level' => 'Несложный',
                'time' => '60 минут',
                'players' => '2-5 игроков',
                'money' => 'от 1600 рублей',
                'special' => 'Страшный',
                'specialStyle' => 'dark',
                'active' => false,
            ],
        ];

        return view('public.index', [
            'quests' => $quests,
        ]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function schedule()
    {
        $schedule = Schedule::getNextDays();
        return view('public.schedule', [
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
