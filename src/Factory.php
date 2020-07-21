<?php

namespace Docs;

use Closure;
use Docs\Contracts\Doc;
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
     * @param  string $path
     * @return Doc
     */
    public function make($abstract): Doc
    {
        $doc = $this->resolveClassDoc($abstract);

        if ($this->isClass($abstract)) {
            $reflector = new ReflectionClass($abstract);
        }

        if ($this->isFile($abstract)) {
            dd('TODO');
        }

        return $this->makeFrom($doc, $abstract, $reflector ?? null);
    }

    /**
     * Determine if abstract is class.
     *
     * @param  string $abstract
     * @return bool
     */
    public function isClass($abstract)
    {
        return class_exists($abstract);
    }

    /**
     * Determine if abstract is file.
     *
     * @param  string $abstract
     * @return bool
     */
    public function isFile($abstract)
    {
        return false;
    }

    /**
     * Create new ReflectionDoc from class for reflector.
     *
     * @param  string    $doc
     * @param  string    $class
     * @param  Reflector $reflector
     * @return Doc
     */
    public function makeFrom($doc, $class, Reflector $reflector = null): Doc
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
        if (instance_of($class, Doc::class)) {
            return $class;
        }

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
