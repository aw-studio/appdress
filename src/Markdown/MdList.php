<?php

namespace Docs\Markdown;

class MdList extends Item
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function toMarkdown()
    {
        return implode("\n", $this->renderItems($this->items));
    }

    public function renderItems($items, $depth = 1)
    {
        $lines = [];

        foreach ($items as $item) {
            if (is_array($item)) {
                $lines = array_merge($lines, $this->renderItems($item, $depth + 1));
            } else {
                $line = '';

                for ($i = 1; $i < $depth; $i++) {
                    $line .= "\t";
                }

                $line .= "- {$item}";

                $lines[] = $line;
            }
        }

        return $lines;
    }
}
