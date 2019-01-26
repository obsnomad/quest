<?php

namespace App\Http\Controllers;

use App\Mail\Certificate as MailCertificate;
use App\Models\Quest;
use App\Models\QuestLocation;

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
    public function gift()
    {
        return view('public.gift');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function giftSend()
    {
        $data = \Request::validate([
            'email' => 'required_without_all:phone,vk|nullable|email',
            'phone' => 'required_without_all:email,vk|nullable|regex:/\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}/',
            'vk' => 'required_without_all:email,phone|nullable',
        ], [
            '*.required_without_all' => 'Введите хотя бы один контакт.',
            'phone.regex' => 'Введите корректный номер телефона.',
        ]);
        try {
            \Mail::send(new MailCertificate($data));
        } catch (\Exception $e) {

        }
        return response()->json([
            'result' => 'Ваш запрос отправлен. Мы свяжемся с Вами в ближайшее время.',
        ]);
    }
}
