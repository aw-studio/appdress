<?php

namespace Docs\Docs\Concerns;

use Docs\Markdown\Table;
use Docs\Support\Markdown;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock\Tag;
use ReflectionClass;
use ReflectionMethod;

trait DescribesTests
{
    /**
     * Describes tests.
     *
     * @return array
     */
    public function describeTests()
    {
        $tests = $this->getTestCases();

        if ($tests->isEmpty()) {
            return;
        }

        return [
            $this->subTitle('Tests'),
            $this->testCasesTable($tests),
        ];
    }

    /**
     * Undocumented function.
     *
     * @param  Collection $tests
     * @return Table
     */
    protected function testCasesTable(Collection $tests)
    {
        $rows = $tests->map(function (ReflectionMethod $method) {
            return [
                ucfirst(str_replace('_', ' ', Str::snake($method->name))),
                '✔️',
            ];
        })->toArray();

        return Markdown::table([
            'Description', '',
        ], $rows);
    }

    /**
     * Gets test cases.
     *
     * @return Collection
     */
    protected function getTestCases(): Collection
    {
        if (! $docBlock = $this->getDocBlock()) {
            return collect([]);
        }

        return collect($docBlock->getTags())->filter(function (Tag $tag) {
            return $tag->getName() == 'test'
                && class_exists($tag->getDescription()->getBodyTemplate());
        })->map(function (Tag $tag) {
            $reflector = new ReflectionClass($tag->getDescription()->getBodyTemplate());

            return $this->getTestCasesFromClass($reflector);
        })->flatten();
    }

    /**
     * Find test cases in class.
     *
     * @param  ReflectionClass $reflector
     * @return Collection
     */
    protected function getTestCasesFromClass(ReflectionClass $reflector): Collection
    {
        if (! instance_of($reflector->name, \PHPUnit\Framework\TestCase::class)) {
            return collect([]);
        }

        return $this->getOwnPublicMethods($reflector)->filter(function (ReflectionMethod $method) {
            return $this->isMethodATestCase($method);
        });
    }

    /**
     * Determines if a method is a test case.
     *
     * @param  ReflectionMethod $method
     * @return bool
     */
    protected function isMethodATestCase(ReflectionMethod $method)
    {
        if (Str::startsWith($method->name, 'test')) {
            return true;
        }

        if (! $docBlock = $this->getDocBlock($method)) {
            return false;
        }

        return ! collect($docBlock->getTags())->filter(function (Tag $tag) {
            return $tag->getName() == 'test';
        })->isEmpty();
    }
}
