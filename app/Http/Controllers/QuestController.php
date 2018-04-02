<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Quest;
use App\Models\Schedule;

class QuestController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quests = Quest::active()
            ->get();
        return view('public.quests.index', [
            'quests' => $quests,
        ]);
    }

    /**
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $quest = Quest::active()
            ->where('slug', $slug)
            ->first();
        if(!$quest) {
            return redirect()->route('quests');
        }
        $path = public_path("images/quests/{$quest->slug}");
        $pictures = collect(\File::exists($path) ? \File::allFiles($path) : [])
            ->map(function ($value) {
                /** @var \SplFileInfo $value */
                return str_replace('\\', '/', str_replace(public_path(), '', $value->getPathname()));
            })
            ->unique();
        return view('public.quests.show', [
            'quest' => $quest,
            'pictures' => $pictures,
        ]);
    }


}
