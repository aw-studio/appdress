<?php

namespace Docs\Contracts;

interface Markdownable
{
    /**
     * Parse to markdown.
     *
     * @return string
     */
    public function toMarkdown();
}
