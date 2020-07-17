<?php

namespace Docs\Blocks\Model;

use Docs\Blocks\ClassBlock;
use ReflectionMethod;

class RelationsBlock extends ClassBlock
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
        return $this->makeBlock(RelationshipBlock::class, $method);
    }
}
