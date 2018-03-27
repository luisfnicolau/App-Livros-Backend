<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // it just generates stuff, so only on local env
        if ($this->app->environment() == 'local') {
            $this->app->register('Hesto\MultiAuth\MultiAuthServiceProvider');
            $this->app
                ->register('Laracasts\Generators\GeneratorsServiceProvider');
        }
    }
}
