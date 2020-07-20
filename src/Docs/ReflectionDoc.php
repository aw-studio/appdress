<?php

namespace Docs\Docs;

use Docs\Contracts\Doc;
use Docs\Contracts\Parser;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;
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
        if (! $docBlock = $this->getDocBlock($reflector)) {
            return collect([]);
        }

        $summary = collect([$docBlock->getSummary()]);

        if (! $description = $docBlock->getDescription()) {
            return $summary;
        }

        $summary[] = $description->getBodyTemplate();

        return $summary;
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
