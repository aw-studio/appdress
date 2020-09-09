<?php

namespace Docs;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        Route::get(config('appdress.route_prefix'), DocsController::class.'@show')->name('appdress.welcome');
        Route::get(config('appdress.route_prefix').'/class/{class}', DocsController::class.'@show')->name('appdress.class');
        Route::get(config('appdress.route_prefix').'/{namespace}/{file}', DocsController::class.'@markdown')->name('appdress.markdown');
    }
}
