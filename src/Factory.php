<?php

namespace Docs;

use Docs\Blocks\ClassBlock;
use Docs\Blocks\Model\ModelBlock;
use Docs\Contracts\Block;
use ReflectionClass;

class Factory
{
    /**
     * Creates new Block object from class.
     *
     * @param  string     $path
     * @return ClassBlock
     */
    public function make($class): Block
    {
        $block = $this->resolveBlock($class);

        $reflection = new ReflectionClass($class);

        return $this->makeFrom($block, $class, $reflection);
    }

    public function makeFrom($block, $class, $reflection)
    {
        return app()->make($block, [
            'class'      => $class,
            'reflection' => $reflection,
        ]);
    }

    public function resolveBlock($class)
    {
        return ModelBlock::class;
    }
}
