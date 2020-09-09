<?php

namespace Docs\Docs\Routes;

use Closure;
use Docs\Docs\BaseDoc;
use Docs\Support\Markdown;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Str;

abstract class BaseRoutesDoc extends BaseDoc
{
    public function describe()
    {
        return [
            $this->routesTable(),
        ];
    }

    protected function routesTable()
    {
        $routes = $this->getRoutes()->map(function (Route $route) {
            $route->actionDescription = $this->describeRouteAction($route);
            $route->actionSortKey = (string) $route->actionDescription;

            return $route;
        })->sortBy('actionSortKey');

        $rows = $routes->map(function (Route $route) {
            return [
                Markdown::code("/{$route->uri}"),
                Markdown::code(implode('`,`', $route->methods())),
                $route->actionDescription,
                $route->getName(),
            ];
        });

        return Markdown::table([
            'uri', 'methods', 'action', 'name',
        ], $rows->toArray());
    }

    public function describeRouteAction(Route $route)
    {
        if ($controller = $this->getRouteController($route)) {
            return Markdown::link(class_basename($controller), route('appdress.class', ['class' => $controller]));
        }

        if (! array_key_exists('uses', $route->action)) {
            return;
        }
        $uses = $route->action['uses'];

        if ($uses instanceof Closure) {
            return Markdown::code('Closure');
        }
    }

    /**
     * Get registered routes.
     *
     * @return Collection
     */
    public function getRoutes()
    {
        return collect(RouteFacade::getRoutes()->getRoutes());
    }

    public function getRouteController(Route $route)
    {
        if (! $uses = $route->action['uses']) {
            return;
        }

        if (! is_string($uses)) {
            return;
        }

        if (! $controller = Str::parseCallback($uses)[0]) {
            return;
        }

        return $controller;
    }
}
