<?php

namespace App\Http\Controllers\Bot;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class TestController extends Controller
{
    protected function messageNew() {
        $vkAccount = (object)collect(\VKAPI::call('users.get', [
            'user_ids' => $this->object->user_id,
            'fields' => 'screen_name',
        ]))->first();
        $text = mb_strtolower($this->object->body);
        $message = null;
        if(strpos($text, 'привет') !== false) {
            $message = 'Привет' . ($vkAccount ? ', ' . $vkAccount->first_name : '') . '. Я тебе могу что-нибудь рассказать.';
        }
        elseif(strpos($text, 'расскажи') !== false) {
            $data = collect(json_decode(file_get_contents('http://umorili.herokuapp.com/api/random?num=1')))->first();
            if($data && $data->elementPureHtml) {
                $message = html_entity_decode(strip_tags($data->elementPureHtml));
                $message .= "\n\nЯ нашёл это на {$data->site}";
            }
            else {
                $message = 'Извини, я не знаю, что ответить.';
            }
        }
        else {
            $message = 'Я тебя не понимаю, говори яснее.';
        }
        if($message) {
            \VKAPI::call('messages.send', [
                'user_id' => $this->object->user_id,
                'message' => $message,
            ]);
        }
        echo "ok";
    }
}
