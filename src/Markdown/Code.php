<?php

namespace Docs\Markdown;

class Code extends Item
{
    protected $code;

    protected $lang;

    public function __construct($code, $lang = null)
    {
        $this->code = $code;
        $this->lang = $lang;
    }

    public function toMarkdown()
    {
        return "`{$this->code}`";
    }
}
