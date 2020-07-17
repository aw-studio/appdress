<?php

namespace Docs;

use ParsedownExtra;

class Block
{
    protected $title;

    protected $description = [];

    protected $children = [];

    protected $layer;

    public function __construct(int $layer, $title, array $description = [], array $children = [])
    {
        $this->layer = $layer;
        $this->title = $title;
        $this->description = $description;
        $this->setChildren($children);
    }

    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function addDescription($description)
    {
        $this->description[] = $description;
    }

    public function appendDescription($description)
    {
        $this->addDescription($description);

        return $this;
    }

    public function prependDescription($description)
    {
        array_unshift($this->description, $description);

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function child(self $child)
    {
        $this->children[] = $child;

        return $this;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setChildren(array $children)
    {
        foreach ($children as $child) {
            $this->child($child);
        }

        return $this;
    }

    public function toMarkdown()
    {
        $markdown = '';

        for ($i = 0; $i < $this->layer; $i++) {
            $markdown .= '#';
        }

        $markdown .= " {$this->title}\n\n";

        $markdown .= implode("\n\n", $this->description);

        foreach ($this->children as $child) {
            $markdown .= "\n\n".$child->toMarkdown();
        }

        return $markdown;
    }

    public function __toString()
    {
        return (new ParsedownExtra)->text($this->toMarkdown());
    }
}
