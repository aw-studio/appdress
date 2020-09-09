<?php

namespace Docs\Docs\Model;

use Docs\Docs\ClassDoc;
use Docs\Support\Markdown;
use ReflectionMethod;

class ModelDoc extends ClassDoc
{
    use Concerns\DescribesDatabase,
        Concerns\describeAccessors,
        Concerns\DescribeMutators,
        Concerns\DescribeRelations,
        Concerns\DescribesScopes;

    /**
     * Describe Model.
     *
     * @return array
     */
    public function describe()
    {
        return [
            $this->getIntroduction(),
            $this->describeDatabase(),
            $this->describeAccessors(),
            $this->describeMutators(),
            $this->describeRelations(),
            $this->describeScopes(),
            $this->getOtherMethods(),
        ];
    }

    public function getOtherMethods()
    {
        $filtered = $this->getAccessorMethods()
            ->merge($this->getMutatorsMethods())
            ->merge($this->getRelationsMethods())
            ->merge($this->getScopeMethods());

        $methods = $this->getOwnPublicMethods()->filter(function (ReflectionMethod $method) use ($filtered) {
            return ! $filtered->contains($method);
        });

        $rows = $methods->map(function ($method) {
            return [
                Markdown::code($method->name),
                $this->getSummary($method)->implode("\n"),
            ];
        })->toArray();

        return [
            $this->subTitle('Methods'),
            Markdown::table([
                'Methods', 'Description',
            ], $rows),
        ];
    }

    public function getCasts()
    {
        $model = new $this->class;
        $casts = $model->getCasts();

        if (! $model->usesTimestamps()) {
            return $casts;
        }

        return array_merge($casts, [
            $model->getCreatedAtColumn() => 'datetime',
            $model->getUpdatedAtColumn() => 'datetime',
        ]);
    }
}
