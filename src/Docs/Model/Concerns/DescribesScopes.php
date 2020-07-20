<?php

namespace Docs\Docs\Model\Concerns;

use Docs\Markdown\Table;
use Docs\Support\Markdown;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionMethod;

trait DescribesScopes
{
    /**
     * Describe database.
     *
     * @return array
     */
    public function describeScopes()
    {
        if ($this->getScopeMethods()->isEmpty()) {
            return;
        }

        return [
            $this->subTitle('Scopes'),
            $this->scopesTable(),
        ];
    }

    /**
     * Scopes table.
     *
     * @return Table
     */
    public function scopesTable()
    {
        $rows = $this->getScopeMethods()->map(function ($method) {
            $docBlock = null;
            if ($comment = $method->getDocComment()) {
                $docBlock = $this->factory->create($comment);
            }

            return [
                lcfirst(Str::replaceFirst('scope', '', $method->name)),
                $docBlock ? $docBlock->getSummary() : null,
            ];
        })->toArray();

        return Markdown::table([
            'column', 'description',
        ], $rows);
    }

    /**
     * Get scope methods.
     *
     * @return Collection
     */
    public function getScopeMethods(): Collection
    {
        return $this->getMethods()->filter(function ($method) {
            return $this->isScopeMethod($method);
        });
    }

    /**
     * Determines if method is a scope.
     *
     * @param  ReflectionMethod $method
     * @return bool
     */
    public function isScopeMethod(ReflectionMethod $method)
    {
        return Str::startsWith($method->name, 'scope');
    }
}
