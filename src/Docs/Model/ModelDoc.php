<?php

namespace Docs\Docs\Model;

use Docs\Docs\ClassDoc;

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
