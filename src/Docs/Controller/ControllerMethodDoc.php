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
            $this->describeDependencies(),
            $this->describeRequest(),
        ];
    }

    /**
     * Describe dependencies.
     *
     * @return array
     */
    protected function describeDependencies()
    {
        if (! $dependenciesTable = $this->dependenciesTable()) {
            return;
        }

        return [
            $this->subTitle('Dependencies'),
            $dependenciesTable,
        ];
    }

    /**
     * Dependencies table.
     *
     * @return Table
     */
    protected function dependenciesTable()
    {
        $rows = [];

        $dependencies = $this->getParameters()->map(function ($param) {
            if (! $type = $this->paramTypeName($param)) {
                return;
            }
            if (! class_exists($type)) {
                return;
            }
            if (instance_of($type, Request::class)) {
                return;
            }

            return $this->reflectParameterClass($param);
        })->filter();

        foreach ($dependencies as $dependency) {
            $rows[] = [
                Markdown::code($dependency->name),
                $this->getSummary($dependency)->implode("\n"),
            ];
        }

        if (empty($rows)) {
            return;
        }

        return Markdown::table([
            'Dependency', 'Description',
        ], $rows);
    }

    /**
     * Describe route.
     *
     * @return array
     */
    protected function describeRoute()
    {
        if (! $route = $this->getRoute($this->reflector)) {
            return;
        }

        $items = [
            Markdown::code($route->methods()[0]),
            Markdown::code('/'.$route->uri),
        ];

        if ($route->getName()) {
            $items[] = Markdown::code($route->getName());
        }

        return [
            implode(' ', $items),
        ];
    }

    /**
     * Get route uri.
     *
     * @return void
     */
    protected function getUri()
    {
        if (! $route = $this->getRoute($this->reflector)) {
            return;
        }

        return $route->uri;
    }
}
