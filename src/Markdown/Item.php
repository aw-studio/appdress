<?php

namespace Docs\Markdown;

use Docs\Contracts\Markdownable;

abstract class Item implements Markdownable
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
