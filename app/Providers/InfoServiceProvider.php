<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class InfoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path() . '/Helpers/HelperString.php';
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        App::bind('info', function() {
            return new \App\Helpers\Info;
        });
    }
}
