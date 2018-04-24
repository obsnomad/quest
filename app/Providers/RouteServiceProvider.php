<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    protected $namespaceAdmin = 'App\Http\Controllers\Admin';
    protected $namespaceBot = 'App\Http\Controllers\Bot';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapBotRoutes();

        $this->mapWebRoutes();
        $this->mapWebNoCsrfRoutes();

        $this->mapAdminRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "web-no-csrf" routes for the application.
     *
     * These routes all receive session state, etc.
     *
     * @return void
     */
    protected function mapWebNoCsrfRoutes()
    {
        Route::middleware('web-no-csrf')
             ->namespace($this->namespace)
             ->group(base_path('routes/web-no-csrf.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "bot" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapBotRoutes()
    {
        Route::group(['as' => 'bot.'], function() {
            Route::prefix('bot')
                ->middleware('bot')
                ->namespace($this->namespaceBot)
                ->group(base_path('routes/bot.php'));
        });
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::group(['as' => 'admin.'], function() {
            Route::prefix('admin')
                ->middleware('admin')
                ->namespace($this->namespaceAdmin)
                ->group(base_path('routes/admin.php'));
        });
    }
}
