<?php

namespace Docs\Contracts;

interface Parser
{
    public function toMarkdown(Doc $doc);

    public function toHtml(Doc $doc);
}
