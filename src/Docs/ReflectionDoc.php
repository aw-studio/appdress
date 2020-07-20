<?php

namespace Docs\Docs;

use Docs\Contracts\Doc;
use Docs\Contracts\Parser;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionClass;
use Reflector;

abstract class ReflectionDoc extends BaseDoc
{
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
     * @param  Parser    $parser
     * @param  string    $class
     * @param  Reflector $reflector
     * @return void
     */
    public function __construct(Parser $parser, string $class, Reflector $reflector)
    {
        parent::__construct($parser);
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
}
