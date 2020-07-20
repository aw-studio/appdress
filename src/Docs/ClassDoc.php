<?php

namespace Docs\Docs;

use Docs\Contracts\Parser;
use ReflectionClass;
use ReflectionMethod;

class ClassDoc extends ReflectionDoc
{
    public function __construct(Parser $parser, string $class, ReflectionClass $reflection)
    {
        parent::__construct($parser, $class, $reflection);
    }

    public function getChildren()
    {
        return $this->getMethods()->map(function ($method) {
            return $this->makeMethodBlock($method);
        });
    }

    public function getMethods()
    {
        return collect($this->reflection->getMethods())
            ->filter(function ($method) {
                return $this->shouldDocumentMethod($method);
            });
    }

    protected function shouldDocumentMethod(ReflectionMethod $method)
    {
        return $method->class === $this->class;
    }

    protected function makeMethodBlock(ReflectionMethod $method)
    {
        return $this->makeBlock(MethodDoc::class, $method);
    }
}
