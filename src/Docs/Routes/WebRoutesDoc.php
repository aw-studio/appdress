<?php

namespace Docs\Docs\Routes;

use Docs\Contracts\Engine;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;

class WebRoutesDoc extends BaseRoutesDoc
{
    /**
     * Create new WebRoutesDoc instance.
     *
     * @param Engine $engine
     */
    public function __construct(Engine $engine)
    {
        parent::__construct($engine, base_path('routes/web.php'));
    }

    public function title()
    {
        return 'Web Routes';
    }

    /**
     * Get registered routes.
     *
     * @return Collection
     */
    public function getRoutes()
    {
        return parent::getRoutes()->filter(function (Route $route) {
            if (! $prefix = $route->action['prefix'] ?? null) {
                return false;
            }

            return $prefix != 'api'
                && ! Str::startsWith($route->uri, '_')
                && ! Str::startsWith($route->uri, 'admin');
        });
    }
}
