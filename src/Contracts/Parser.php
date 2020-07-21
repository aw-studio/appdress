<?php

namespace Docs\Contracts;

interface Parser
{
    /**
     * Parse doc to markdown.
     *
     * @param  Doc    $doc
     * @return string
     */
    public function toMarkdown(Doc $doc);

    /**
     * Parse markdown to Html.
     *
     * @param  string $markdown
     * @param  bool   $withToc
     * @return string
     */
    public function toHtml($markdown, bool $withToc = true);
}
