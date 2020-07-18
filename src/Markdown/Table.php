<?php

namespace Docs\Markdown;

class Table extends Item
{
    protected $rows = [];

    public function __construct(array $headers, array $rows)
    {
        $this->headers = $headers;
        $this->rows = $rows;
    }

    public function toMarkdown()
    {
        $rows = [];

        $rows[] = $this->renderRow($this->headers);
        $rows[] = $this->renderSplitter();

        foreach ($this->rows as $row) {
            $rows[] = $this->renderRow($row);
        }

        return implode("\n", $rows);
    }

    public function renderRow($columns)
    {
        return '| '.implode(' | ', $columns).' |';
    }

    public function renderSplitter()
    {
        $splitter = '|';

        foreach ($this->headers as $column) {
            $splitter .= '---|';
        }

        return $splitter;
    }
}
