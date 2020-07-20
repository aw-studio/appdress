<?php

namespace Docs;

use Closure;
use Docs\Docs\ClassDoc;
use Docs\Docs\ReflectionDoc;
use ReflectionClass;
use Reflector;

class Factory
{
    /**
     * Bindings.
     *
     * @var array
     */
    protected $bindings = [];

    /**
     * Bind dependency.
     *
     * @param  string $dependency
     * @param  string $doc
     * @return $this
     */
    public function bind($dependency, $doc)
    {
        $this->bindings[$dependency] = $doc;

        return $this;
    }

    /**
     * Creates new ReflectionDoc instance from class.
     *
     * @param  string        $path
     * @return ReflectionDoc
     */
    public function make($class): ReflectionDoc
    {
        $block = $this->resolveClassDoc($class);

        $reflector = new ReflectionClass($class);

        return $this->makeFrom($block, $class, $reflector);
    }

    /**
     * Create new ReflectionDoc from class for reflector.
     *
     * @param  string        $doc
     * @param  string        $class
     * @param  Reflector     $reflector
     * @return ReflectionDoc
     */
    public function makeFrom($doc, $class, Reflector $reflector): ReflectionDoc
    {
        return app()->make($doc, [
            'class'     => $class,
            'reflector' => $reflector,
        ]);
    }

    /**
     * Resolve class doc.
     *
     * @param  string $class
     * @return string
     */
    public function resolveClassDoc($class)
    {
        foreach ($this->bindings as $dependency => $binding) {
            if ($binding instanceof Closure && $binding($class)) {
                return $dependency;
            }
            if (instance_of($class, $dependency)) {
                return $binding;
            }
        }

        return ClassDoc::class;
    }
}
