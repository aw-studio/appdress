<?php

namespace Docs\Blocks\Model;

use Docs\Blocks\ClassBlock;

class ModelBlock extends ClassBlock
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
            $this->makeBlock(RelationsBlock::class),
        ];
    }
}
