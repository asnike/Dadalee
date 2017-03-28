<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GoogleApiProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        app()->bind('GoogleApi', function(){
            return new \App\Libraries\Google\GoogleApi;
        });
    }
}
