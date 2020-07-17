<?php

namespace Docs\Blocks;

use Docs\Contracts\Parser;
use ReflectionMethod;

class MethodBlock extends BaseBlock
{
    public function __construct(Parser $parser, string $class, ReflectionMethod $reflection)
    {
        parent::__construct($parser, $class, $reflection);
    }

    public function getTitle()
    {
        return $this->reflection->name;
    }
}
