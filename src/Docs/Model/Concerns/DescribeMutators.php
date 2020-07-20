<?php

namespace Docs\Docs\Model\Concerns;

use Docs\Markdown\Table;
use Docs\Support\Markdown;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionMethod;

trait DescribeMutators
{
    /**
     * Describe database.
     *
     * @return array
     */
    public function describeMutators()
    {
        if ($this->getMutatorsMethods()->isEmpty()) {
            return;
        }

        return [
            $this->subTitle('Mutators'),
            $this->mutatorsTable(),
        ];
    }

    /**
     * Mutators table.
     *
     * @return Table
     */
    public function mutatorsTable()
    {
        $rows = $this->getMutatorsMethods()->map(function ($method) {
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
     * Get mutators methods.
     *
     * @return Collection
     */
    public function getMutatorsMethods(): Collection
    {
        return $this->getMethods()->filter(function ($method) {
            return $this->isMutatorMethod($method);
        });
    }

    /**
     * Determines if method is a scope.
     *
     * @param  ReflectionMethod $method
     * @return bool
     */
    protected function isMutatorMethod(ReflectionMethod $method)
    {
        $method = $method->getName();

        return Str::startsWith($method, 'set')
            && Str::endsWith($method, 'Attribute')
            && $method !== 'setAttribute'
            && $method !== 'setClassCastableAttribute';
    }

    /**
     * Get attribute name from accessor method.
     *
     * @param  ReflectionMethod $method
     * @return string
     */
    protected function getMutatorsAttribute(ReflectionMethod $method)
    {
        return Str::snake(
            Str::replaceFirst('set', '', Str::replaceLast('Attribute', '', $method->name)),
        );
    }
}
