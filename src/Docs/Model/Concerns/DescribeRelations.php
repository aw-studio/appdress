<?php

namespace Docs\Docs\Model\Concerns;

use Docs\Markdown\Model\Relationship;
use Docs\Markdown\Table;
use Docs\Support\Markdown;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use ReflectionMethod;

trait DescribeRelations
{
    /**
     * Describe Relations.
     *
     * @return array
     */
    public function describeRelations()
    {
        if ($this->getRelationsMethods()->isEmpty()) {
            return;
        }

        return [
            $this->subTitle('Relationships'),
            $this->relationsTable(),
        ];
    }

    /**
     * Relations table.
     *
     * @return Table
     */
    public function relationsTable()
    {
        $rows = $this->getRelationsMethods()->map(function ($method) {
            return [
                $method->name,
                new Relationship($method->getReturnType()->getName()),
                $this->getSummary($method)->implode("\n"),
            ];
        })->toArray();

        return Markdown::table([
            'column', 'type', 'description',
        ], $rows);
    }

    /**
     * Get relations methods.
     *
     * @return Collection
     */
    public function getRelationsMethods(): Collection
    {
        return $this->getMethods()->filter(function ($method) {
            return $this->isRelationsMethod($method);
        });
    }

    /**
     * Determines if method is a scope.
     *
     * @param  ReflectionMethod $method
     * @return bool
     */
    protected function isRelationsMethod(ReflectionMethod $method)
    {
        if (! $returnType = $method->getReturnType()) {
            return false;
        }

        return instance_of($returnType->getName(), Relation::class);
    }
}
