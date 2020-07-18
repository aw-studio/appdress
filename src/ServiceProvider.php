<?php

namespace Docs;

use Docs\Docs\Model\ModelDoc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'docs');

        $this->app->singleton('docs.parser', \Docs\Parser\Parser::class);

        $this->app->singleton('docs.factory', \Docs\Factory::class);

        $this->app->bind(\Docs\Contracts\Parser::class, 'docs.parser');

        $this->app->afterResolving('docs.factory', function ($factory) {
            $factory->bind(Model::class, ModelDoc::class);
        });
    }
}
