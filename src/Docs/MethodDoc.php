<?php

namespace Docs\Docs;

use Docs\Contracts\Engine;
use ReflectionMethod;

class MethodDoc extends ReflectionDoc
{
    /**
     * Method reflector.
     *
     * @var ReflectionMethod
     */
    protected $reflector;

    /**
     * Create new MethodDoc instance.
     *
     * @param  Engine           $engine
     * @param  string           $class
     * @param  ReflectionMethod $reflector
     * @return void
     */
    public function __construct(Engine $engine, string $class, ReflectionMethod $reflector)
    {
        parent::__construct($engine, $class, $reflector);
    }

    /**
     * Doc title.
     *
     * @return string
     */
    public function title()
    {
        return $this->reflector->name;
    }

    /**
     * Describe method.
     *
     * @return array
     */
    public function describe()
    {
        return [
            $this->getSummary(),
            $this->describeDependencies($this->reflector),
        ];
    }
}
