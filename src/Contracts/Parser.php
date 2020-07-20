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
     * Parse Doc to Html.
     *
     * @param  Doc    $doc
     * @return string
     */
    public function toHtml(Doc $doc);
}
