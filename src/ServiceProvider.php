<?php

namespace Docs;

use Docs\Docs\Controller\ControllerDoc;
use Docs\Docs\Mail\MailDoc;
use Docs\Docs\Model\ModelDoc;
use Docs\Engines\ParserEngine;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Database\Eloquent\Model;
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
        $this->registerPublishes();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'docs');

        $this->app->singleton('docs.parser', \Docs\Parser\Parser::class);
        $this->app->singleton('docs.parser.engine', function ($app) {
            $engine = new ParserEngine(
                $app['files'],
                $app['docs.parser']
            );

            return $engine;
        });
        $this->app->singleton('docs.factory', \Docs\Factory::class);
        $this->app->singleton('docs.nav', \Docs\Navigation\Navigation::class);
        $this->app->bind(\Docs\Contracts\Parser::class, 'docs.parser');
        $this->app->bind(\Docs\Contracts\Engine::class, 'docs.parser.engine');

        $this->app->afterResolving('docs.factory', function ($factory) {
            $factory->bind(Model::class, ModelDoc::class);
            $factory->bind(Mailable::class, MailDoc::class);

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
    }

    /**
     * Register publishes.
     *
     * @return void
     */
    public function registerPublishes()
    {
        $this->publishes([
            __DIR__.'/../publish/config/docs.php' => config_path('docs.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../publish/Providers/DocsServiceProvider.php' => app_path('Providers/DocsServiceProvider.php'),
        ], 'provider');

        $this->mergeConfigFrom(
            __DIR__.'/../publish/config/docs.php',
            'docs'
        );
    }
}
