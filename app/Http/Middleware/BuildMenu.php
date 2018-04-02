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
		Menu::make('menuIcon', function ($menu) {
			$menu
				->add('<span class="fas fa-bars"></span>', ['class' => 'navbar-mobile-menu'])
				->link->attr(['id' => 'navbar-mobile-menu']);
		});
		Menu::make('menuMain', function ($menu) {
			$menu
				->add('Расписание', ['route' => ['schedule', 'absolute' => false]])
				->active('schedule/*');
			$menu
				->add('Квесты', ['route' => ['quests', 'absolute' => false]])
				->active('quests/*');
			$menu
				->add('Подарочные карты', ['route' => ['index', 'absolute' => false]])
				->active('giftcards/*');
			$menu
				->add('Контакты', ['route' => ['index', 'absolute' => false]])
				->active('contacts/*');
		});
		return $next($request);
	}
}
