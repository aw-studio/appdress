<?php

namespace Docs\Docs;

use Docs\Contracts\Engine;
use Docs\Support\Markdown;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

class ClassDoc extends ReflectionDoc
{
    use Concerns\DescribesTests;

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
            $this->getIntroduction(),

            $this->describeDependencies(
                $this->reflectClassMethod($this->reflector, '__construct')
            ),

            $this->describeTests(),

            $this->describeMethods(
                $this->withoutMagic($this->getOwnPublicMethods())
            )->prepend($this->subTitle('Methods')),
        ];
    }

    /**
     * Get introduction.
     *
     * @return array|null
     */
    public function getIntroduction()
    {
        $summary = $this->getSummary();

        if (! $summary) {
            return;
        }

        return [
            Markdown::title('Introduction', 2),
            $summary,
        ];
    }

    /**
     * Get class methods.
     *
     * @param  ReflectionClass $reflector
     * @return Collection
     */
    public function getMethods(ReflectionClass $reflector = null): Collection
    {
        $reflector = $this->resolveReflector($reflector);

        return collect($reflector->getMethods());
    }

    /**
     * Get own public methods.
     *
     * @param  ReflectionClass $reflector
     * @return Collection
     */
    public function getPublicMethods(ReflectionClass $reflector = null)
    {
        return $this->publicMethods(
            $this->getMethods($reflector)
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
     * @param  ReflectionClass $reflector
     * @return Collection
     */
    public function getOwnMethods(ReflectionClass $reflector = null): Collection
    {
        $reflector = $this->resolveReflector($reflector);

        $traitMethods = $this->getTraitMethods();

        return $this->getMethods($reflector)->filter(function (ReflectionMethod $method) use ($reflector, $traitMethods) {
            if (! $traitMethods->filter(fn ($trait) => $trait->name == $method->name)->isEmpty()) {
                return false;
            }

            return $method->class === $reflector->name;
        });
    }

    public function getTraitMethods(ReflectionClass $reflector = null)
    {
        $reflector = $this->resolveReflector($reflector);

        return collect($reflector->getTraits())->map(function (ReflectionClass $trait) {
            return $trait->getMethods();
        })->flatten();
    }

    /**
     * Get own public methods.
     *
     * @param  ReflectionClass $reflector
     * @return Collection
     */
    public function getOwnPublicMethods(ReflectionClass $reflector = null)
    {
        return $this->publicMethods(
            $this->getOwnMethods($reflector)
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
            return $this->subDoc(MethodDoc::class, $method, 2);
        });
    }
}
