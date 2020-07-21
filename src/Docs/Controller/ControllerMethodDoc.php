<?php

namespace Docs\Docs\Controller;

use Docs\Docs\MethodDoc;
use Docs\Support\Markdown;
use Illuminate\Http\Request;

class ControllerMethodDoc extends MethodDoc
{
    use Concerns\DescribesRequest,
        Concerns\ManagesRoutes;

    /**
     * Describe controller method.
     *
     * @return array
     */
    public function describe()
    {
        return [
            $this->describeRoute(),
            $this->getSummary(),
            $this->describeRequest(),
            $this->describeDependencies($this->reflector),
        ];
    }

    /**
     * Get parameter dependencies.
     *
     * @return Collection
     */
    protected function getDependencies()
    {
        return parent::getDependencies()->map(function ($param) {
            if (! $type = $this->paramTypeName($param)) {
                return;
            }
            if (! class_exists($type)) {
                return;
            }
            if (instance_of($type, Request::class)) {
                return;
            }

            return $param;
        })->filter();
    }

    /**
     * Describe route.
     *
     * @return array
     */
    protected function describeRoute()
    {
        $routes = $this->getRoutesForMethod($this->reflector);
        if ($routes->isEmpty()) {
            return;
        }

        return $routes->map(function ($route) {
            $items = [
                Markdown::code($route->methods()[0]),
                Markdown::code('/'.$route->uri),
            ];

            if ($route->getName()) {
                $items[] = Markdown::code($route->getName());
            }

            return implode(' ', $items);
        })->implode('<br>');
    }
}
