<?php

namespace Docs\Docs;

use Docs\Contracts\Parser;
use ReflectionClass;
use ReflectionMethod;

class ClassDoc extends BaseDoc
{
    public function __construct(Parser $parser, string $class, ReflectionClass $reflection)
    {
        parent::__construct($parser, $class, $reflection);
    }

    public function getChildren(): array
    {
        $methods = [];

        foreach ($this->reflection->getMethods() as $method) {
            if ($this->shouldDocumentMethod($method)) {
                $methods[] = $this->makeMethodBlock($method);
            }
        }

        return $methods;
    }

    protected function shouldDocumentMethod(ReflectionMethod $method)
    {
        return $method->class === $this->class;
    }

    protected function makeMethodBlock(ReflectionMethod $method)
    {
        return $this->makeBlock(MethodBlock::class, $method);
    }
}
