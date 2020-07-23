<?php

namespace Docs\Docs;

use Docs\Contracts\Doc;
use Docs\Contracts\Engine;
use Docs\Docs\Concerns\DescribesDependencies;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use Reflector;

abstract class ReflectionDoc extends BaseDoc
{
    use DescribesDependencies;

    /**
     * Documented class.
     *
     * @var string
     */
    protected $class;

    /**
     * Reflector.
     *
     * @var Reflector
     */
    protected $reflector;

    /**
     * Create new ReflectionDoc instance.
     *
     * @param  Engine    $engine
     * @param  string    $class
     * @param  Reflector $reflector
     * @return void
     */
    public function __construct(Engine $engine, string $class, Reflector $reflector)
    {
        parent::__construct($engine, get_path_from_namespace($class));
        $this->class = $class;
        $this->reflector = $reflector;
        $this->factory = DocBlockFactory::createInstance();
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
     * Get summary from reflector.
     *
     * @param  Reflector|null $reflector
     * @return Collection
     */
    public function getSummary(Reflector $reflector = null): Collection
    {
        if (! $reflector) {
            $reflector = $this->reflector;
        }

        if (! $docBlock = $this->getDocBlock($reflector)) {
            return $this->getInvokeSummary($reflector);
        }

        $summary = collect([$docBlock->getSummary()]);

        if (! $description = $docBlock->getDescription()) {
            return $summary->merge(
                $this->getInvokeSummary($reflector)
            );
        }

        $summary[] = $description->getBodyTemplate();

        return $summary->merge(
            $this->getInvokeSummary($reflector)
        );
    }

    /**
     * Get parameter summary.
     *
     * @param  ReflectionMethod    $method
     * @param  ReflectionParameter $parameter
     * @return array|null
     */
    public function getParameterSummary(ReflectionMethod $method, ReflectionParameter $parameter)
    {
        if (! $docBlock = $this->getDocBlock($method)) {
            return;
        }

        foreach ($docBlock->getTags() as $tag) {
            if (! method_exists($tag, 'getVariableName')) {
                continue;
            }
            if ($tag->getVariableName() == $parameter->name) {
                return $tag->getDescription()->getBodyTemplate();
            }
        }
    }

    /**
     * Get invoke summary.
     *
     * @param  Reflector  $reflector
     * @return Collection
     */
    public function getInvokeSummary(Reflector $reflector)
    {
        if (! $reflector instanceof ReflectionClass) {
            return collect([]);
        }

        $method = collect($reflector->getMethods())->first(function ($method) {
            return $method->name == '__invoke';
        });

        if (! $method) {
            return collect([]);
        }

        return $this->getSummary($method);
    }

    /**
     * Make Doc from reflector.
     *
     * @param  string    $class
     * @param  Reflector $reflector
     * @return Doc
     */
    protected function subDoc($class, Reflector $reflector = null): self
    {
        $doc = app('docs.factory')->makeFrom(
            $class,
            $this->class,
            $reflector ?: $this->reflector
        );

        $doc->setDepth($this->depth + 1);

        return $doc;
    }

    /**
     * Get doc block instance.
     *
     * @param  ReflectionClass|ReflectionClassConstant|ReflectionFunctionAbstract|ReflectionProperty $reflector
     * @return DocBlock
     */
    public function getDocBlock($reflector = null)
    {
        if (! $reflector) {
            $reflector = $this->reflector;
        }

        if (! $comment = $reflector->getDocComment()) {
            return;
        }

        return $this->docBlock = $this->factory->create($comment);
    }

    /**
     * Create new reflector for class.
     *
     * @param  ReflectionParameter  $reflector
     * @return ReflectionClass|null
     */
    public function reflectParameterClass(ReflectionParameter $reflector)
    {
        if (! $name = $this->paramTypeName($reflector)) {
            return;
        }

        if (! class_exists($name)) {
            return;
        }

        return new ReflectionClass($name);
    }

    /**
     * Find method reflector by name.
     *
     * @param  ReflectionClass       $reflector
     * @param  string                $method
     * @return ReflectionMethod|null
     */
    public function reflectClassMethod(ReflectionClass $reflector, $method)
    {
        foreach ($reflector->getMethods() as $reflectionMethod) {
            if ($reflectionMethod->name == $method) {
                return $reflectionMethod;
            }
        }
    }

    /**
     * Get type name from parameter reflection.
     *
     * @param  ReflectionParameter $reflector
     * @return string|null
     */
    public function paramTypeName(ReflectionParameter $reflector)
    {
        if (! $type = $reflector->getType()) {
            return;
        }

        return $type->getName();
    }

    /**
     * Resolve reflector.
     *
     * @param  Reflector $reflector
     * @return Reflector
     */
    protected function resolveReflector(Reflector $reflector = null)
    {
        if (! $reflector) {
            return $this->reflector;
        }

        return $reflector;
    }

    /**
     * Get method parameters.
     *
     * @param  ReflectionMethod|null $reflector
     * @return Collection
     */
    public function getParameters(ReflectionMethod $reflector = null)
    {
        $reflector = $this->resolveReflector($reflector);

        if ($reflector instanceof ReflectionClass) {
            $reflector = $this->reflectClassMethod($reflector, '__construct');
        }

        if (! $reflector) {
            return collect([]);
        }

        return collect($reflector->getParameters());
    }
}
