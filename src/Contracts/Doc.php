<?php

namespace Docs\Contracts;

interface Doc
{
    public function getTitle();

    public function getDescription();

    public function getChildren();

    public function getDepth(): int;
}
