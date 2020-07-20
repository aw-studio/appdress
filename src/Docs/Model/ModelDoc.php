<?php

namespace Docs\Docs\Model;

use Docs\Docs\ClassDoc;
use Docs\Support\Markdown;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\DocBlock\Tags\PropertyRead;

class ModelDoc extends ClassDoc
{
    public function addDescription(): array
    {
        return [
            $this->describeDatabase(),
            $this->describeAccessors(),
            $this->describeMutators(),
            $this->describeRelations(),
            $this->describeScopes(),
        ];
    }

    public function describeDatabase()
    {
        return [
            Markdown::title('Database', $this->depth + 1),
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

            //dd($tag, $docBlock);

            return $description->getBodyTemplate();
        }
    }

    public function describeRelations()
    {
        $relations = $this->makeBlock(RelationsDoc::class);
        if (! $relations->getMethods()->isEmpty()) {
            return $relations;
        }
    }

    public function describeScopes()
    {
        $scopes = $this->makeBlock(ScopesDoc::class);
        if (! $scopes->getMethods()->isEmpty()) {
            return $scopes;
        }
    }

    public function describeAccessors()
    {
        $scopes = $this->makeBlock(AccessorsDoc::class);
        if (! $scopes->getMethods()->isEmpty()) {
            return $scopes;
        }
    }

    public function describeMutators()
    {
        $scopes = $this->makeBlock(MutatorsDoc::class);
        if (! $scopes->getMethods()->isEmpty()) {
            return $scopes;
        }
    }

    public function getTable()
    {
        return (new $this->class)->getTable();
    }

    public function getCasts()
    {
        $model = new $this->class;
        $casts = $model->getCasts();

        if (! $model->usesTimestamps()) {
            return $casts;
        }

        return array_merge($casts, [
            $model->getCreatedAtColumn() => 'datetime',
            $model->getUpdatedAtColumn() => 'datetime',
        ]);
    }
}
