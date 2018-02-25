<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;
use Reliese\Coders\CodersServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Blade::if('permissions', function() {
            return \Auth::user()->hasPermissions(func_get_args());
        });
        \Blade::if('anypermission', function() {
            return \Auth::user()->hasPermissions(func_get_args(), 'or');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register(IdeHelperServiceProvider::class);
            $this->app->register(CodersServiceProvider::class);
        }
    }
}
