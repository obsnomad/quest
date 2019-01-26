<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Menu;
use Config;

class BuildMenu
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		Menu::make('menuMain', function ($menu) {
			$menu
				->add('Главная', ['route' => ['index', 'absolute' => false]])
				->active('/*');
			$menu
				->add('Расписание', ['route' => ['schedule', 'absolute' => false]])
				->active('schedule/*');
			$menu
				->add('Квесты', ['route' => ['quests', 'absolute' => false]])
				->active('quests/*');
			$menu
				->add('Сертификаты', ['route' => ['gift', 'absolute' => false]])
				->active('gift/*');
		});
		return $next($request);
	}
}
