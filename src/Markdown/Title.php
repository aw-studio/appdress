<?php

namespace Docs\Markdown;

class Title extends Item
{
    protected $title;

    protected $depth = 1;

    public function __construct($title, $depth = 1)
    {
        $this->title = $title;
        $this->depth = $depth;
    }

    public function toMarkdown()
    {
        return implode('', [
            $this->renderPrefix(),
            $this->title,
        ]);
    }

    public function renderPrefix()
    {
        $prefix = '';

        for ($i = 0; $i < $this->depth; $i++) {
            $prefix .= '#';
        }

        return "{$prefix} ";
    }
}
