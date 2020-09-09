<?php

namespace Docs\Docs\Concerns;

use Docs\Support\Markdown;
use ReflectionMethod;
use ReflectionParameter;

trait DescribesDependencies
{
    /**
     * Describe dependencies.
     *
     * @return array
     */
    protected function describeDependencies(ReflectionMethod $method = null)
    {
        if (! $dependenciesTable = $this->dependenciesTable($method)) {
            return;
        }

        return [
            //$this->subTitle('Dependencies'),
            $dependenciesTable,
        ];
    }

    /**
     * Get parameter dependencies.
     *
     * @return Collection
     */
    protected function getDependencies()
    {
        return $this->getParameters();
    }

    /**
     * Dependencies table.
     *
     * @return Table
     */
    protected function dependenciesTable(ReflectionMethod $method = null)
    {
        if (! $method) {
            return;
        }

        $rows = $this->getDependencies()->map(function (ReflectionParameter $parameter) use ($method) {
            return [
                $this->describeDependencyType($parameter),
                $this->describeDependency($parameter, $method),
            ];
        });

        if ($rows->isEmpty()) {
            return;
        }

        return Markdown::table([
            'Dependency', 'Description', //'Test',
        ], $rows->toArray());
    }

    protected function describeDependencyType(ReflectionParameter $parameter)
    {
        if ($class = $this->reflectParameterClass($parameter)) {
            if ($class->isInternal()) {
                return Markdown::code(class_basename($class->name));
            }

            return Markdown::link(class_basename($class->name), route('appdress.class', ['class' => $class->name]));
        }

        if ($type = $parameter->getType()) {
            return Markdown::code($type->getName());
        }
    }

    protected function describeDependency(ReflectionParameter $parameter, ReflectionMethod $method)
    {
        if ($summary = $this->getParameterSummary($method, $parameter)) {
            return $summary;
        }
        if ($type = $this->reflectParameterClass($parameter)) {
            return $this->getSummary($type)->implode("\n");
        }
    }
}
