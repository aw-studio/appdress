<?php

namespace Docs\Parser;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use ReflectionMethod;

class ModelParser extends Parser
{
    protected function makeMethodBlock(ReflectionMethod $method)
    {
        $block = parent::makeMethodBlock($method);

        if (Str::startsWith($method->name, 'scope')) {
            return $this->describeScope($block, $method);
        }

        if ($this->isRelationMethod($method)) {
            return $this->describeRelationship($block, $method, $method->getReturnType()->getName());
        }

        return $block;
    }

    public function describeRelationship($block, ReflectionMethod $method, $relationship)
    {
        $block->addDescription('Relationship: **'.class_basename($relationship).'**');

        return $block;
    }

    protected function describeScope($block, ReflectionMethod $method)
    {
        return $block;
    }

    protected function isRelationMethod(ReflectionMethod $method): bool
    {
        if (! $type = $method->getReturnType()) {
            return false;
        }

        return $this->classInstanceof($type->getName(), Relation::class);
    }

    protected function classInstanceof($class, $match)
    {
        $parent = get_parent_class($class);

        if (! $parent) {
            return false;
        }

        if ($parent == $match) {
            return true;
        }

        return $this->classInstanceof($parent, $match);
    }
}
