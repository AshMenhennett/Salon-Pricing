<?php

namespace App\Providers;

use Validator;
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
        Validator::extend('dollars', function ($attribute, $value, $parameters) {
            // format: 100.23
            return preg_match('/^\d*(\.\d{2})?$/', $value);
        });
        Validator::extend('allowed_dollar_amount', function ($attribute, $value, $parameters) {
            // no more than 5 dollar places and 2 cent places
            // 99999.99 is legal, 199999.99 is not
            return preg_match('/^\d{0,5}(\.\d{2})?$/', $value);
        });
        Validator::extend('no_accounts', function ($attribute, $value, $parameters) {
            // returns true if there are no accounts. This is a temporary implementation to restrict access to app to one user.
            return count(\App\User::all()) === 0;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (env('APP_ENV') === 'production') {
            $this->app['request']->server->set('HTTPS', true);
        }
    }
}
