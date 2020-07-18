<?php

namespace Docs;

use Docs\Contracts\Block;
use Docs\Contracts\Doc;
use ReflectionClass;

class Factory
{
    protected $bindings = [];

    public function bind($dependency, $doc)
    {
        $this->bindings[$dependency] = $doc;

        return $this;
    }

    /**
     * Creates new Block object from class.
     *
     * @param  string   $path
     * @return ClassDoc
     */
    public function make($class): Doc
    {
        $block = $this->resolveClassDoc($class);

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

    public function resolveClassDoc($class)
    {
        foreach ($this->bindings as $dependency => $binding) {
            if (instance_of($class, $dependency)) {
                return $binding;
            }
        }

        return ClassDoc::class;
    }
}
