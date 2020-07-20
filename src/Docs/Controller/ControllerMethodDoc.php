<?php

namespace Docs\Docs\Controller;

use Docs\Docs\MethodDoc;
use Docs\Support\Markdown;
use Illuminate\Http\Request;

class ControllerMethodDoc extends MethodDoc
{
    use Concerns\DescribesRequest,
        Concerns\ManagesRoutes;

    public function title()
    {
        return Markdown::code(parent::title());
    }

    /**
     * Describe controller method.
     *
     * @return array
     */
    public function describe()
    {
        return [
            $this->describeRoute(),
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
        return [
            $this->subTitle('Dependencies'),
            $this->dependenciesTable(),
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
            'Uri: '.Markdown::code('/'.$route->uri),
        ];

        if ($route->getName()) {
            $items[] = 'Uri: '.Markdown::code($route->getName());
        }

        return [
            $this->subTitle('Route'),
            Markdown::list($items),
        ];
    }
}
