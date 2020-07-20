<?php

namespace Docs\Docs\Model;

use Docs\Docs\ClassDoc;
use Docs\Support\Markdown;
use Illuminate\Support\Str;
use ReflectionMethod;

class ScopesDoc extends ClassDoc
{
    public function getTitle()
    {
        return 'Scopes';
    }

    public function getDescription(): array
    {
        return [
            $this->description(),
        ];
    }

    public function getChildren()
    {
        return [];
    }

    public function description()
    {
        $rows = $this->getMethods()->map(function ($method) {
            $docBlock = null;
            if ($comment = $method->getDocComment()) {
                $docBlock = $this->factory->create($comment);
            }

            return [
                lcfirst(Str::replaceFirst('scope', '', $method->name)),
                $docBlock ? $docBlock->getSummary() : null,
            ];
        })->toArray();

        return Markdown::table([
            'column', 'description',
        ], $rows);

        return Markdown::link('Scopes', 'https://laravel.com/docs/7.x/eloquent#local-scopes');
    }

    protected function shouldDocumentMethod(ReflectionMethod $method)
    {
        return Str::startsWith($method->getName(), 'scope');
    }

    protected function makeMethodBlock(ReflectionMethod $method)
    {
        return $this->makeBlock(ScopeDoc::class, $method);
    }
}
