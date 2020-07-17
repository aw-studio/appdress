<?php

namespace Docs\Contracts;

interface Parser
{
    public function toMarkdown(Block $block);

    public function toHtml(Block $block);
}
