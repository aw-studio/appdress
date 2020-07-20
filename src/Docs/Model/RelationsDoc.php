<?php

namespace Docs\Docs\Model;

use Docs\Docs\ClassDoc;
use Docs\Markdown\Model\Relationship;
use Docs\Support\Markdown;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionMethod;

class RelationsDoc extends ClassDoc
{
    public function getTitle()
    {
        return 'Relationships';
    }

    public function getDescription(): array
    {
        return [
            $this->description(),
            $this->relationsTable(),
        ];
    }

    public function description()
    {
        return 'Relationships';
    }

    public function relationsTable()
    {
        $rows = $this->getMethods()->map(function ($method) {
            $docBlock = $this->factory->create($method->getDocComment());

            return [
                $method->name,
                new Relationship($method->getReturnType()->getName()),
                $docBlock ? $docBlock->getSummary() : null,
            ];
        })->toArray();

        return Markdown::table([
            'column', 'type', 'description',
        ], $rows);
    }

    public function getChildren()
    {
        return [];
    }

    protected function shouldDocumentMethod(ReflectionMethod $method)
    {
        if (! $returnType = $method->getReturnType()) {
            return false;
        }

        return instance_of($returnType->getName(), Relation::class);
    }

    protected function makeMethodBlock(ReflectionMethod $method)
    {
        return $this->makeBlock(RelationDoc::class, $method);
    }
}
