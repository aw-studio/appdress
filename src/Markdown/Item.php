<?php

namespace Docs\Markdown;

use Docs\Contracts\MarkdownItem;

abstract class Item implements MarkdownItem
{
    public static function markdown(...$arguments)
    {
        return (new static(...$arguments))->toMarkdown();
    }

    public function __toString()
    {
        return $this->toMarkdown();
    }
}
