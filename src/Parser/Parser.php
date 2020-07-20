<?php

namespace Docs\Parser;

use Docs\Contracts\Doc;
use Docs\Contracts\Markdownable;
use Docs\Contracts\Parser as ParserContract;
use ParsedownExtra;

class Parser implements ParserContract
{
    /**
     * ParsedownExtra instance.
     *
     * @var ParsedownExtra
     */
    protected $parsedown;

    /**
     * Create new Parser instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->parsedown = new ParsedownExtra;
    }

    /**
     * Parse Doc instance to markdown.
     *
     * @param  Doc    $doc
     * @return string
     */
    public function toMarkdown(Doc $doc)
    {
        $lines = collect([]);

        $lines[] = $doc->getTitle();
        $lines = $lines->merge(
            $doc->getDescription($doc)
        );

        return $lines->map(function ($line) {
            if ($line instanceof Markdownable) {
                return $line->toMarkdown();
            }

            return $line;
        })->implode("\n\n");
    }

    /**
     * Parse Doc instance to Html.
     *
     * @param  Doc    $doc
     * @return string
     */
    public function toHtml(Doc $doc)
    {
        $html = $this->parsedown->text(
            $this->toMarkdown($doc)
        );

        return "<div class=\"md\">{$html}</div>";
    }
}
