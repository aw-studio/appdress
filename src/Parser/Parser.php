<?php

namespace Docs\Parser;

use Docs\Contracts\Block;
use Docs\Contracts\Parser as ParserContract;
use ParsedownExtra;

class Parser implements ParserContract
{
    use Concerns\ParsesMarkdown;

    protected $parsedown;

    public function __construct()
    {
        $this->parsedown = new ParsedownExtra;
    }

    public function toMarkdown(Block $block)
    {
        $lines = [];

        $lines[] = $this->parseMarkdownTitle($block);

        $lines = array_merge($lines, $this->parseMarkdownDescription($block));

        $lines = array_merge($lines, $this->parseMarkdownChildren($block));

        return implode("\n\n", $lines);
    }

    public function toHtml(Block $block)
    {
        return $this->parsedown->text(
            $this->toMarkdown($block)
        );
    }
}
