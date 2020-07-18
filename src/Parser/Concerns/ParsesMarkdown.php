<?php

namespace Docs\Parser\Concerns;

use Docs\Contracts\Doc;
use Docs\Contracts\MarkdownItem;
use Docs\Support\Markdown;

trait ParsesMarkdown
{
    public function parseMarkdownTitle(Doc $doc)
    {
        return Markdown::title(
            $doc->getTitle(),
            $doc->getDepth()
        )->toMarkdown();
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
