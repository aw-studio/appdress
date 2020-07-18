<?php

namespace Docs\Parser;

use Docs\Contracts\Doc;
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

    public function toMarkdown(Doc $doc)
    {
        $lines = [];

        $lines[] = $this->parseMarkdownTitle($doc);

        $lines = array_merge($lines, $this->parseMarkdownDescription($doc));

        $lines = array_merge($lines, $this->parseMarkdownChildren($doc));

        return implode("\n\n", $lines);
    }

    public function toHtml(Doc $doc)
    {
        return $this->parsedown->text(
            $this->toMarkdown($doc)
        );
    }
}
