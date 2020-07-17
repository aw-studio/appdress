<?php

namespace Docs\Markdown;

use Illuminate\Support\Str;

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
            $this->renderLink(),
        ]);
    }

    public function renderLink()
    {
        return '<a name="'.Str::slug($this->title).'"></a>';
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
