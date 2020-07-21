<?php

namespace Docs\Parser;

use Docs\Contracts\Doc;
use Docs\Contracts\Markdownable;
use Docs\Contracts\Parser as ParserContract;
use Illuminate\Support\Str;
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
     * @param  bool   $withToc
     * @return string
     */
    public function toMarkdown(Doc $doc, $withToc = false)
    {
        $lines = collect([]);

        $lines[] = $doc->getTitle();
        $lines = $lines->merge(
            $doc->getDescription($doc)
        );

        $markdown = $lines->map(function ($line) {
            if ($line instanceof Markdownable) {
                return $line->toMarkdown();
            }

            return $line;
        })->implode("\n\n");

        //dd($markdown);

        if ($withToc) {
            return $this->applyToc($markdown);
        }

        return $markdown;
    }

    /**
     * Parse Doc instance to Html.
     *
     * @param  Doc    $doc
     * @return string
     */
    public function toHtml(Doc $doc, $withToc = true)
    {
        $html = $this->parsedown->text(
           $this->toMarkdown($doc, $withToc)
        );

        return "<div class=\"md\">{$html}</div>";
    }

    /**
     * Apply table of contents to markdown.
     *
     * @param  string $markdown
     * @return string
     */
    protected function applyToc($markdown)
    {
        preg_match_all('/(?m)^#{2,3}(?!#)(.*)/', $markdown, $matches);

        //dd($this->makeToc($matches[1]));

        return $markdown;
    }

    public function makeToc(array $matches)
    {
        if (empty($matches[0])) {
            return;
        }

        $rows = [];
        foreach ($matches[1] as $key => $raw) {
            $title = $this->getHeadingTitle($raw);
        }
    }

    protected function getHeadingTitle($heading)
    {
        if (Str::contains($heading, '<a')) {
            return explode('<a', $heading)[0];
        }

        return $heading;
    }
}
