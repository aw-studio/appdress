<?php

namespace Docs\Docs\Model;

use Docs\Docs\ClassDoc;
use Docs\Support\Markdown;
use Illuminate\Support\Facades\Schema;

class ModelDoc extends ClassDoc
{
    public function prependDescription(): array
    {
        return [
            'Database table: `'.$this->getTable().'`',
            $this->describeSchema(),
        ];
    }

    public function describeSchema()
    {
        $rows = [];

        $schema = Schema::connection('docs_sqlite');

        $columns = $schema->getColumnListing($this->getTable());

        if (empty($columns)) {
            return;
        }

        foreach ($columns as $column) {
            $rows[] = [
                $column,
                '`'.$schema->getColumnType($this->getTable(), $column).'`',
            ];
        }

        return Markdown::table([
            'column', 'type',
        ], $rows);
    }

    public function getChildren(): array
    {
        $children = [];
        $relations = $this->makeBlock(RelationsDoc::class);

        if (! $relations->getMethods()->isEmpty()) {
            $children[] = $relations;
        }

        return $children;
    }

    public function getTable()
    {
        return (new $this->class)->getTable();
    }
}
