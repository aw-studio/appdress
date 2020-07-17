<?php

namespace Docs\Blocks\Model;

use Docs\Blocks\MethodBlock;
use Docs\Markdown\Model\Relationship;

class RelationshipBlock extends MethodBlock
{
    public function addDescription(): array
    {
        return [
            $this->describeRelationship(
                $this->reflection->getReturnType()->getName()
            ),
        ];
    }

    public function describeRelationship($relationClass)
    {
        return new Relationship($relationClass);
    }
}
