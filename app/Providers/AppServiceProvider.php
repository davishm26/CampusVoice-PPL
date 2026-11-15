<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale(config('app.locale', 'id'));

        Carbon::macro('wib', function () {
            /** @var \Carbon\Carbon $this */
            return $this->copy()
                ->timezone(config('app.display_timezone', 'Asia/Jakarta'))
                ->locale('id');
        });
    }
}
