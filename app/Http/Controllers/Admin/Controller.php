<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    const PAGE_COUNT = 20;

    protected $adminValues;

    public function __construct()
    {
        $this->adminValues = array_replace([
            'menuCollapsed' => false,
            'gameActiveTab' => 0,
            'gameTableMode' => 'show',
        ], (array)json_decode(\Cookie::get('adminValues'), true));

        app('config')->set('adminlte.collapse_sidebar', $this->getAdminValue('menuCollapsed'));
        if($this->getAdminValue('gameActiveTab') > 2) {
            $this->setAdminValue('gameActiveTab', 0);
        }

        \View::share('adminValues', (object)$this->adminValues);
    }

    /**
     * Get admin value from cookies
     * @param $key
     * @return mixed
     */
    protected function getAdminValue($key) {
        return @$this->adminValues[$key];
    }

    /**
     * Save admin value to cookies
     * @param $key
     * @param $value
     * @return void
     */
    protected function setAdminValue($key, $value) {
        $this->adminValues[$key] = $value;
        \Cookie::set('adminValues', $this->adminValues);
    }
}
