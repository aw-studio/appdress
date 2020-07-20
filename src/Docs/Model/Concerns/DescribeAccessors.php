<?php

namespace Docs\Docs\Model\Concerns;

use Docs\Markdown\Table;
use Docs\Support\Markdown;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionMethod;

trait DescribeAccessors
{
    /**
     * Describe database.
     *
     * @return array
     */
    public function describeAccessors()
    {
        if ($this->getAccessorMethods()->isEmpty()) {
            return;
        }

        return [
            $this->subTitle('Accessors'),
            $this->accessorsTable(),
        ];
    }

    /**
     * Accessors table.
     *
     * @return Table
     */
    public function accessorsTable()
    {
        $rows = $this->getAccessorMethods()->map(function ($method) {
            return [
                '`'.$this->getAccessorsAttribute($method).'`',
                $this->getSummary($method)->implode("\n"),
            ];
        })->toArray();

        return Markdown::table([
            'Attribute', 'Description',
        ], $rows);
    }

    /**
     * Get scope methods.
     *
     * @return Collection
     */
    public function getAccessorMethods(): Collection
    {
        return $this->getMethods()->filter(function ($method) {
            return $this->isAccessorMethod($method);
        });
    }

    /**
     * Determines if method is a scope.
     *
     * @param  ReflectionMethod $method
     * @return bool
     */
    protected function isAccessorMethod(ReflectionMethod $method)
    {
        return Str::startsWith($method->getName(), 'get')
            && Str::endsWith($method->getName(), 'Attribute')
            && $method->getName() !== 'getAttribute';
    }

    /**
     * Get attribute name from accessor method.
     *
     * @param  ReflectionMethod $method
     * @return string
     */
    protected function getAccessorsAttribute(ReflectionMethod $method)
    {
        return Str::snake(
            Str::replaceFirst('get', '', Str::replaceLast('Attribute', '', $method->name)),
        );
    }
}
