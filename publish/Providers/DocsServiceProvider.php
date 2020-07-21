<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DocsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $nav = $this->app['docs.nav'];

        $nav->section('Models')->describe(app_path('Models'));
        $nav->section('Controller')->describe(app_path('Http/Controllers'));
    }
}
