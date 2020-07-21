<?php

namespace Docs\Contracts;

interface Engine
{
    /**
     * Get markdown from doc.
     *
     * @param  Doc    $doc
     * @return string
     */
    public function getMarkdown(Doc $doc);

    /**
     * Get html from doc.
     *
     * @param  Doc    $doc
     * @return string
     */
    public function getHtml(Doc $doc);
}
