<?php

namespace Docs\Docs\Routes;

use Docs\Contracts\Engine;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;

class ApiRoutesDoc extends BaseRoutesDoc
{
    /**
     * Create new ApiRoutesDoc instance.
     *
     * @param Engine $engine
     */
    public function __construct(Engine $engine)
    {
        parent::__construct($engine, base_path('routes/api.php'));
    }

    public function title()
    {
        return 'Api Routes';
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

            return Str::contains($prefix, 'api');
        });
    }
}
