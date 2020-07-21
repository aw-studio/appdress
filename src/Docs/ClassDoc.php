<?php

namespace Docs\Docs;

use Docs\Contracts\Engine;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class ClassDoc extends ReflectionDoc
{
    /**
     * Class reflector.
     *
     * @var ReflectionClass
     */
    protected $reflector;

    /**
     * Create new ClassDoc instance.
     *
     * @param  Engine          $engine
     * @param  string          $class
     * @param  ReflectionClass $reflector
     * @return void
     */
    public function __construct(Engine $engine, string $class, ReflectionClass $reflector)
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
        return class_basename($this->class);
    }

    /**
     * Describe class.
     *
     * @return array
     */
    public function describe()
    {
        return [
            $this->getSummary(),
            $this->describeDependencies(
                $this->reflectClassMethod($this->reflector, '__construct')
            ),
            $this->describeMethods(
                $this->withoutMagic($this->getOwnMethods())
            ),
        ];
    }

    /**
     * Get class methods.
     *
     * @return Collection
     */
    public function getMethods(): Collection
    {
        return collect($this->reflector->getMethods());
    }

    /**
     * Get own public methods.
     *
     * @return Collection
     */
    public function getPublicMethods()
    {
        return $this->publicMethods(
            $this->getMethods()
        );
    }

    /**
     * Filter public methods.
     *
     * @param  Collection $methods
     * @return Collection
     */
    protected function publicMethods(Collection $methods)
    {
        return $methods->filter(function (ReflectionMethod $method) {
            return $method->getModifiers() === ReflectionMethod::IS_PUBLIC;
        });
    }

    /**
     * Get own class methods.
     *
     * @return Collection
     */
    public function getOwnMethods(): Collection
    {
        return $this->getMethods()->filter(function (ReflectionMethod $method) {
            return $method->class === $this->class;
        });
    }

    /**
     * Get own public methods.
     *
     * @return Collection
     */
    public function getOwnPublicMethods()
    {
        return $this->publicMethods(
            $this->getOwnMethods()
        );
    }

    public function withoutMagic(Collection $methods)
    {
        return $methods->filter(function (ReflectionMethod $method) {
            return ! Str::startsWith($method->name, '__');
        });
    }

    /**
     * Describe own public methods.
     *
     * @param  Collection $methods
     * @return Collection
     */
    protected function describeMethods(Collection $methods)
    {
        return $methods->map(function ($method) {
            return $this->subDoc(MethodDoc::class, $method);
        });
    }
}
