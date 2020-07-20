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
        $lines = collect([]);

        $lines[] = $this->parseMarkdownTitle($doc);
        $lines = $lines->merge(
            $this->parseMarkdownDescription($doc)
        );

        return $lines->flatten()->implode("\n\n");
    }

    public function toHtml(Doc $doc)
    {
        return $this->parsedown->text(
            $this->toMarkdown($doc)
        );
    }
}
