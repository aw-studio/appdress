<?php

namespace Docs;

use Docs\Docs\Controller\ControllerDoc;
use Docs\Docs\Model\ModelDoc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Support\Str;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'docs');

        $this->app->singleton('docs.parser', \Docs\Parser\Parser::class);

        $this->app->singleton('docs.factory', \Docs\Factory::class);

        $this->app->singleton('docs.nav', \Docs\Navigation\Navigation::class);

        $this->app->bind(\Docs\Contracts\Parser::class, 'docs.parser');

        $this->app->afterResolving('docs.factory', function ($factory) {
            $factory->bind(Model::class, ModelDoc::class);

            $factory->bind(ControllerDoc::class, function ($class) {
                return Str::endsWith($class, 'Controller');
            });
        });

        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Boot application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['config']->set('database.connections.docs_sqlite', [
            'driver'                  => 'sqlite',
            'database'                => ':memory:',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ]);

        $this->app->resolving(ModelDoc::class, function () {
            Artisan::call('migrate', ['--database' => 'docs_sqlite']);
        });

        $this->app['docs.nav']->section('Models')->describe(app_path('Models'));
        $this->app['docs.nav']->section('Controller')->describe(app_path('Http/Controllers'));

        //dd($this->app['docs.nav']);
    }
}
