<?php

namespace Docs\Docs\Controller\Concerns;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use ReflectionMethod;

trait ManagesRoutes
{
    /**
     * Get routes.
     *
     * @return void
     */
    public function getRoutes()
    {
        return collect(RouteFacade::getRoutes()->getRoutes())->filter(function ($route) {
            if (! $controller = $route->action['controller'] ?? null) {
                return false;
            }

            return $controller == $this->class;
        });
    }

    /**
     * Get route controller method name.
     *
     * @param  Route  $route
     * @return string
     */
    public function getRouteControllerMethod(Route $route)
    {
        return last(explode('@', $route->action['uses']));
    }

    /**
     * Get route for method.
     *
     * @param  ReflectionMethod $method
     * @return Route|null
     */
    public function getRoute(ReflectionMethod $method)
    {
        return $this->getRoutes()->first(function ($route) use ($method) {
            return $this->getRouteControllerMethod($route) == $method->name;
        });
    }
}
