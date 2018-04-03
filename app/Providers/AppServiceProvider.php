<?php

namespace App\Providers;

use App\Models\Booking;
use App\Observers\BookingObserver;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Carbon\Carbon;
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
        Booking::observe(BookingObserver::class);

        \Blade::if('permissions', function() {
            return \Auth::user()->hasPermissions(func_get_args());
        });
        \Blade::if('anypermission', function() {
            return \Auth::user()->hasPermissions(func_get_args(), 'or');
        });

        setlocale(LC_ALL, 'Russian');
        Carbon::setToStringFormat('d.m.Y H:i:s');
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
