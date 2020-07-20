<?php

namespace Docs\Docs;

use Docs\Contracts\Doc;
use Docs\Contracts\Markdownable;
use Docs\Contracts\Parser;
use Docs\Markdown\Title;
use Docs\Support\Markdown;

abstract class BaseDoc implements Doc, Markdownable
{
    /**
     * Parser instance.
     *
     * @var Parser
     */
    protected $parser;

    /**
     * Doc depth.
     *
     * @var int
     */
    protected $depth = 1;

    /**
     * Get Doc title.
     *
     * @return string
     */
    abstract public function title();

    /**
     * Describe block.
     *
     * @return array
     */
    abstract public function describe();

    /**
     * Create new BaseDoc instance.
     *
     * @param  Parser $parser
     * @return void
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Create subTitle.
     *
     * @param  string $title
     * @return Title
     */
    public function subTitle($title)
    {
        return Markdown::title($title, $this->depth + 1);
    }

    /**
     * Get title.
     *
     * @return Title
     */
    public function getTitle()
    {
        $title = $this->title();

        if ($title instanceof Title) {
            return $title;
        }

        return Markdown::title($title, $this->depth)->toMarkdown();
    }

    /**
     * Get description.
     *
     * @return Collection
     */
    public function getDescription()
    {
        $description = $this->describe();

        if (is_array($description)) {
            $description = collect($description);
        }

        return $description->flatten();
    }

    /**
     * Set depth.
     *
     * @param  int   $depth
     * @return $this
     */
    public function setDepth(int $depth)
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * Get depth.
     *
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * Parse Doc to markdown.
     *
     * @return string
     */
    public function toMarkdown()
    {
        return $this->parser->toMarkdown($this);
    }

    /**
     * Parse Doc to Html.
     *
     * @return string
     */
    public function toHtml()
    {
        return $this->parser->toHtml($this);
    }

    /**
     * Parse Doc to string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toHtml() ?? '';
    }
}
