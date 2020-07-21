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
        Route::get(config('docs.route_prefix'), DocsController::class.'@index')->name('docs.index');
        Route::get(config('docs.route_prefix').'/class/{class}', DocsController::class.'@class')->name('docs.class');
        Route::get(config('docs.route_prefix').'/{namespace}/{file}', DocsController::class.'@markdown')->name('docs.markdown');
    }
}
