<?php

namespace Docs\Docs\Model\Concerns;

use Docs\Support\Markdown;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\DocBlock\Tags\PropertyRead;

trait DescribesDatabase
{
    /**
     * Describe database.
     *
     * @return array
     */
    public function describeDatabase()
    {
        return [
            $this->subTitle('Database'),
            'Database table: `'.$this->getTable().'`',
            $this->describeSchema(),
        ];
    }

    /**
     * Desribe Schema.
     *
     * @return array
     */
    public function describeSchema()
    {
        $rows = [];

        $schema = Schema::connection('docs_sqlite');

        $columns = $schema->getColumnListing($this->getTable());

        if (empty($columns)) {
            return;
        }

        $casts = $this->getCasts();

        foreach ($columns as $column) {
            $rows[] = [
                $column,
                '`'.$schema->getColumnType($this->getTable(), $column).'`',
                $casts[$column] ?? null,
                $this->describeColumn($column),
            ];
        }

        return Markdown::table([
            'column', 'type', 'cast', 'description',
        ], $rows);
    }

    /**
     * Describe column.
     *
     * @param  [type] $column
     * @return void
     */
    public function describeColumn($column)
    {
        if (! $docBlock = $this->getDocBlock()) {
            return;
        }

        foreach ($docBlock->getTags() as $tag) {
            if (! $tag instanceof PropertyRead) {
                continue;
            }

            if ($tag->getVariableName() != $column) {
                continue;
            }

            if (! $description = $tag->getDescription()) {
                return;
            }

            return $description->getBodyTemplate();
        }
    }

    /**
     * Get models table name.
     *
     * @return string
     */
    public function getTable()
    {
        return (new $this->class)->getTable();
    }
}
