<?php

namespace Docs\Docs\Model\Concerns;

use Docs\Support\Markdown;
use Docs\Support\Schema;
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

        $columns = collect(Schema::getColumnListing($this->getTable()))->mapWithKeys(function ($column) {
            return [$column => Schema::getColumnType($this->getTable(), $column)];
        })->sortDesc();

        if (empty($columns)) {
            return;
        }

        $casts = $this->getCasts();

        foreach ($columns as $column => $type) {
            $rows[] = [
                Markdown::code($column),
                Markdown::code($type),
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
