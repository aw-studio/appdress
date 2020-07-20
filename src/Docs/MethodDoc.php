<?php

namespace Docs\Docs;

use Docs\Contracts\Parser;
use Illuminate\Support\Collection;
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
     * @param  Parser           $parser
     * @param  string           $class
     * @param  ReflectionMethod $reflector
     * @return void
     */
    public function __construct(Parser $parser, string $class, ReflectionMethod $reflector)
    {
        parent::__construct($parser, $class, $reflector);
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
        ];
    }

    /**
     * Get method parameters.
     *
     * @return Collection
     */
    public function getParameters()
    {
        return collect($this->reflector->getParameters());
    }
}
