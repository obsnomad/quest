<?php

namespace App\Http\Controllers\Bot;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $groupId;
    protected $token;
    protected $confirmation;
    protected $object;

    public function index() {
        $botName = preg_replace('/^bot\./', '', \Request::route()->getName());
        $this->groupId = config("bot.$botName.group_id");
        $this->token = config("bot.$botName.token");
        config([
            'VKAPI.access_token' => $this->token,
        ]);
        $this->confirmation = config("bot.$botName.confirmation");
        $data = json_decode(\Request::getContent());
        $type = $data->type;
        $method = camel_case($type);
        $groupId = @$data->group_id;
        $secret = @$data->secret;
        $this->object = @$data->object;

        if($groupId && $groupId != $this->groupId) {
            abort(403, 'Неверный ID сообщества');
        }

        if(!$secret || $secret != config("bot.$botName.secret")) {
            abort(403, 'Неверный секретный ключ');
        }

        return $this->$method();
    }

    protected function confirmation() {
        return $this->confirmation;
    }
}
