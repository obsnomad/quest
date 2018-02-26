<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;

/**
 * Class Controller
 * @package App\Http\Controllers\Admin
 *
 * @method array filterDate()
 * @method array filterQuery()
 */
class Controller extends BaseController
{
    const PAGE_COUNT = 20;

    protected $adminValues;

    protected $filter = [];

    public function __construct()
    {
        $this->adminValues = array_replace([
            'menuCollapsed' => false,
            'bookingActiveTab' => 0,
            'clientActiveTab' => 0,
        ], (array)json_decode(\Cookie::get('adminValues'), true));

        app('config')->set('adminlte.collapse_sidebar', $this->getAdminValue('menuCollapsed'));
        if($this->getAdminValue('gameActiveTab') > 2) {
            $this->setAdminValue('gameActiveTab', 0);
        }

        $this->filter = \Request::input('filter', []);

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

    public function __call($method, $parameters)
    {
        if(substr($method, 0, 6) === 'filter') {
            $input = snake_case(substr($method, 6));
            $filter = \Request::input('filter');
            switch($input) {
                case 'date':
                    return array_filter(explode(' - ', @$filter[$input]));
                    break;
                case 'query':
                    $str = @$filter[$input];
                    return $str ? '%' . preg_replace('/\s/', '%', $str) . '%' : null;
                default:
                    return \Request::input(@$filter[$input]);
            }
        }
        parent::__call($method, $parameters);
    }
}
