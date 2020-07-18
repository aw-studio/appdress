<?php

namespace Docs\Parser\Concerns;

use Docs\Contracts\Doc;
use Docs\Contracts\MarkdownItem;
use Docs\Markdown\Title;

trait ParsesMarkdown
{
    public function parseMarkdownTitle(Doc $doc)
    {
        return Title::markdown($doc->getTitle(), $doc->getDepth());
    }

    public function parseMarkdownDescription(Doc $doc)
    {
        $lines = [];

        foreach ($doc->getDescription() as $description) {
            if ($description instanceof MarkdownItem) {
                $lines[] = $description->toMarkdown();
            } else {
                $lines[] = $description;
            }
        }

        return $lines;
    }

    public function parseMarkdownChildren(Doc $doc)
    {
        $children = [];

        foreach ($doc->getChildren() as $child) {
            $children[] = $child->toMarkdown();
        }

        return $children;
    }
}
