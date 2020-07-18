<?php

namespace Docs\Contracts;

interface Doc
{
    public function getTitle();

    public function getDescription();

    public function getChildren(): array;

    public function getDepth(): int;
}
