<?php

namespace Docs\Docs\Model;

use Docs\Docs\ClassDoc;
use Docs\Support\Markdown;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
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
        return Markdown::list($this->getMethods()->map(function ($method) {
            return Markdown::link($method->name, '#'.Str::slug($method->name));
        }));
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
