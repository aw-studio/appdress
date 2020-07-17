<?php

namespace Docs\Parser\Concerns;

use Docs\Contracts\Block;
use Docs\Contracts\MarkdownItem;
use Docs\Markdown\Title;

trait ParsesMarkdown
{
    public function parseMarkdownTitle(Block $block)
    {
        return Title::markdown($block->getTitle(), $block->getDepth());
    }

    public function parseMarkdownDescription(Block $block)
    {
        $lines = [];

        foreach ($block->getDescription() as $description) {
            if ($description instanceof MarkdownItem) {
                $lines[] = $description->toMarkdown();
            } else {
                $lines[] = $description;
            }
        }

        return $lines;
    }

    public function parseMarkdownChildren(Block $block)
    {
        $children = [];

        foreach ($block->getChildren() as $child) {
            $children[] = $child->toMarkdown();
        }

        return $children;
    }
}
