<?php

namespace Docs\Markdown;

class Link
{
    protected $title;

    protected $url;

    protected $newTab;

    public function __construct($title, $url, $newTab = false)
    {
        $this->title = $title;
        $this->url = $url;
        $this->newTab = $newTab;
    }

    public function __toString()
    {
        return "[{$this->title}]({$this->url})";
    }
}
