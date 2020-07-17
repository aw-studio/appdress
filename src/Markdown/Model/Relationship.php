<?php

namespace Docs\Markdown\Model;

use Docs\Markdown\Item;

class Relationship extends Item
{
    protected $relationship;

    public function __construct(string $relationship)
    {
        $this->relationship = $relationship;
    }

    public function toMarkdown()
    {
        return $this->relationship;
    }
}
