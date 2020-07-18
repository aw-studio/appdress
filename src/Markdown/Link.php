<?php

namespace Docs\Markdown;

use Illuminate\Support\Str;

class Link extends Item
{
    protected $title;

    protected $href;

    public function __construct($title, $href = null)
    {
        $this->title = $title;
        $this->href = $href;

        if (! $this->href) {
            $this->href = Str::slug($title);
        }
    }

    public function toMarkdown()
    {
        return "[$this->title]($this->href)";
    }
}
