<?php

namespace Docs\Docs;

use Docs\Contracts\Parser;
use ReflectionMethod;

class MethodDoc extends BaseDoc
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
