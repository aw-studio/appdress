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

        $this->app->bind(\Docs\Contracts\Parser::class, 'docs.parser');

        $this->app->afterResolving('docs.factory', function ($factory) {
            $factory->bind(Model::class, ModelDoc::class);

            $factory->bind(ControllerDoc::class, function ($class) {
                return Str::endsWith($class, 'Controller');
            });
        });
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
    }
}
