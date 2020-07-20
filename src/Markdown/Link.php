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
        if (! $this->isExternal()) {
            return "[$this->title]($this->href)";
        }

        return '<a href="'.$this->href."\" target=\"_blank\">{$this->title}</a>";
    }

    protected function isExternal()
    {
        $components = parse_url($this->href);

        return ! empty($components['host'])
            && strcasecmp($components['host'], $this->getAppHost()); // empty host will indicate url like '/relative.php'
    }

    public function getAppHost()
    {
        return parse_url(config('app.url'))['host'] ?? null;
    }
}
