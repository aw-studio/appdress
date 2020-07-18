<?php

namespace Docs\Docs\Model;

use Docs\Docs\MethodDoc;
use Docs\Markdown\Model\Relationship;

class RelationDoc extends MethodDoc
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
