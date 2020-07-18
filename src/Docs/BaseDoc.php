<?php

namespace Docs\Docs;

use Docs\Contracts\Doc;
use Docs\Contracts\Parser;
use phpDocumentor\Reflection\DocBlockFactory;

class BaseDoc implements Doc
{
    protected $parser;

    protected $class;

    protected $reflection;

    protected $docBlock;

    protected $depth = 1;

    public function __construct(Parser $parser, string $class, $reflection)
    {
        $this->parser = $parser;
        $this->class = $class;
        $this->reflection = $reflection;
        $this->factory = DocBlockFactory::createInstance();
    }

    public function setDepth(int $depth)
    {
        $this->depth = $depth;

        return $this;
    }

    public function getDepth(): int
    {
        return $this->depth;
    }

    public function getTitle()
    {
        return class_basename($this->class);
    }

    public function getDescription(): array
    {
        $desc = $this->prependDescription();

        if ($summary = $this->getDocBlock()->getSummary()) {
            $desc[] = $summary;
        }

        if ($description = $this->getDocBlock()->getDescription()) {
            $desc[] = $description->getBodyTemplate();
        }

        $desc = array_merge($desc, $this->addDescription());

        return $desc;
    }

    public function prependDescription(): array
    {
        return [];
    }

    public function addDescription(): array
    {
        return [];
    }

    public function getChildren(): array
    {
        return [];
    }

    protected function makeBlock($class, $reflection = null)
    {
        $block = app('docs.factory')->makeFrom(
            $class,
            $this->class,
            $reflection ?: $this->reflection
        );

        $block->setDepth($this->depth + 1);

        return $block;
    }

    public function getDocBlock()
    {
        if ($this->docBlock) {
            return $this->docBlock;
        }

        if (! $comment = $this->reflection->getDocComment()) {
            return;
        }

        return $this->docBlock = $this->factory->create($comment);
    }

    public function toMarkdown()
    {
        return $this->parser->toMarkdown($this);
    }

    public function toHtml()
    {
        return $this->parser->toHtml($this);
    }

    public function __toString()
    {
        return $this->toHtml() ?? '';
    }
}
