<?php

namespace Docs\Docs\Model;

use Docs\Docs\ClassDoc;
use Docs\Support\Markdown;
use Illuminate\Support\Str;
use ReflectionMethod;

class MutatorsDoc extends ClassDoc
{
    public function getTitle()
    {
        return 'Mutators';
    }

    public function getDescription(): array
    {
        return [
            $this->description(),
        ];
    }

    public function description()
    {
        $rows = $this->getMethods()->map(function ($method) {
            $docBlock = $this->factory->create($method->getDocComment());

            return [
                '`'.$this->getAttributeName($method->name).'`',
                $docBlock ? $docBlock->getSummary() : null,
            ];
        })->toArray();

        return Markdown::table([
            'Attribute', 'Description',
        ], $rows);
    }

    public function getAttributeName($method)
    {
        return Str::snake(
            Str::replaceFirst('set', '', Str::replaceLast('Attribute', '', $method)),
        );
    }

    protected function shouldDocumentMethod(ReflectionMethod $method)
    {
        $method = $method->getName();

        return Str::startsWith($method, 'set')
            && Str::endsWith($method, 'Attribute')
            && $method !== 'setAttribute'
            && $method !== 'setClassCastableAttribute';
    }
}
