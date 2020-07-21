<?php

namespace Docs\Support;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Facade;

class Schema extends Facade
{
    /**
     * Determines if migration have been executed for docs connection.
     *
     * @var bool
     */
    protected static $migrated = false;

    /**
     * Get a schema builder instance for the docs_sqlite connection.
     *
     * @return \Illuminate\Database\Schema\Builder
     */
    protected static function getFacadeAccessor()
    {
        static::migrate();

        return static::$app['db']->connection('docs_sqlite')->getSchemaBuilder();
    }

    /**
     * Migrate for the docs connection.
     *
     * @return void
     */
    protected static function migrate()
    {
        if (static::$migrated) {
            return;
        }

        Artisan::call('migrate', ['--database' => 'docs_sqlite']);

        static::$migrated = true;
    }
}
