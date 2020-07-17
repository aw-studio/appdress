<?php

namespace Docs\Blocks;

use Docs\Contracts\Block;
use Docs\Contracts\Parser;
use phpDocumentor\Reflection\DocBlockFactory;

class BaseBlock implements Block
{
    use Concerns\ManagesDoc;

    protected $parser;

    protected $class;

    protected $reflection;

    protected $doc;

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

        if ($summary = $this->getDoc()->getSummary()) {
            $desc[] = $summary;
        }

        if ($description = $this->getDoc()->getDescription()) {
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
