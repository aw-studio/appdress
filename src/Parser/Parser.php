<?php

namespace Docs\Parser;

use Docs\Block;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tag;
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionClass;
use ReflectionMethod;

class Parser
{
    protected $class;

    protected $block;

    protected $reflection;

    protected $factory;

    protected $ignoredTags = [];

    public function __construct($class)
    {
        $this->class = $class;

        $this->factory = DocBlockFactory::createInstance();
    }

    public function ignoreTag($name)
    {
        $this->ignoredTags[] = $name;

        return $this;
    }

    public function parse(): Block
    {
        $reflection = new ReflectionClass($this->class);

        return $this->makeBlock(
            class_basename($this->class),
            $reflection
        );
    }

    public function makeBlock($title, $reflection)
    {
        $layer = $reflection instanceof ReflectionClass ? 1 : 2;
        $block = new Block($layer, $title);

        $this->makeDescription($block, $reflection);

        if ($reflection instanceof ReflectionClass) {
            $this->makeChildren($block, $reflection);
        }

        return $block;
    }

    public function makeChildren(Block $block, ReflectionClass $reflection)
    {
        foreach ($reflection->getMethods() as $method) {
            if (! $this->shouldDescribeMethod($method)) {
                continue;
            }

            $block->child($this->makeMethodBlock($method));
        }
    }

    protected function makeMethodBlock(ReflectionMethod $method)
    {
        return $this->makeBlock($method->name, $method);
    }

    protected function shouldDescribeMethod(ReflectionMethod $method)
    {
        return $method->class === $this->class;
    }

    public function makeDescription($block, $reflection)
    {
        if (! $comment = $reflection->getDocComment()) {
            return;
        }

        $docBlock = $this->factory->create($comment);

        if ($summary = $this->getSummary($docBlock)) {
            $this->addDescription($block, $summary);
        }

        foreach ($docBlock->getTags() as $tag) {
            if (! $description = $this->getDescriptionFromTag($tag->getName(), $tag)) {
                continue;
            }

            $this->addDescription($block, $description);
        }
    }

    public function addDescription(Block $block, $description)
    {
        if (! $description) {
            return;
        }

        if (is_array($description)) {
            foreach ($description as $desc) {
                $block->addDescription($desc);
            }
        } else {
            $block->addDescription($description);
        }
    }

    public function getSummary(DocBlock $docBlock)
    {
        $description = [$docBlock->getSummary()];

        if ($template = $docBlock->getDescription()->getBodyTemplate()) {
            $description[] = $template;
        }

        return $description;
    }

    public function shouldDescribeTag(string $name, Tag $tag): bool
    {
        if (in_array($name, $this->ignoredTags)) {
            return false;
        }

        return true;
    }

    public function getDescriptionFromTag(string $name, Tag $tag)
    {
        if (! $this->shouldDescribeTag($name, $tag)) {
            return;
        }

        $method = $this->getTagDescriptionMethodName($name);

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], [$tag]);
        }
    }

    public function getTagDescriptionMethodName($name)
    {
        return 'describe'.ucfirst($name).'Tag';
    }
}
