<?php

namespace Docs\Docs\Model;

use Docs\Docs\ClassDoc;

class ModelDoc extends ClassDoc
{
    public function prependDescription(): array
    {
        return [
            'Database Table: `'.(new $this->class)->getTable().'`',
        ];
    }

    public function getChildren(): array
    {
        return [
            $this->makeBlock(RelationshipsDoc::class),
        ];
    }
}
