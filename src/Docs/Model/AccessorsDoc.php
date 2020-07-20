<?php

namespace Docs\Docs\Model;

use Docs\Docs\ClassDoc;
use Docs\Support\Markdown;
use Illuminate\Support\Str;
use ReflectionMethod;

class AccessorsDoc extends ClassDoc
{
    public function getTitle()
    {
        return 'Attributes';
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
            $docBlock = null;
            if ($comment = $method->getDocComment()) {
                $docBlock = $this->factory->create($comment);
            }

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
            Str::replaceFirst('get', '', Str::replaceLast('Attribute', '', $method)),
        );
    }

    protected function shouldDocumentMethod(ReflectionMethod $method)
    {
        return Str::startsWith($method->getName(), 'get')
            && Str::endsWith($method->getName(), 'Attribute')
            && $method->getName() !== 'getAttribute';
    }
}
