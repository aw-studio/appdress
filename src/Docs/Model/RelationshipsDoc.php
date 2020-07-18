<?php

namespace Docs\Docs\Model;

use Docs\Docs\ClassDoc;
use ReflectionMethod;

class RelationshipsDoc extends ClassDoc
{
    public function getTitle()
    {
        return 'Relationships';
    }

    public function getDescription(): array
    {
        return [
            'Relationship description',
        ];
    }

    protected function shouldDocumentMethod(ReflectionMethod $method)
    {
        return $method->class === $this->class;
    }

    protected function makeMethodBlock(ReflectionMethod $method)
    {
        return $this->makeBlock(RelationshipDoc::class, $method);
    }
}
