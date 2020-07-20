<?php

namespace Docs\Docs\Model;

use Docs\Docs\MethodDoc;
use Illuminate\Support\Str;

class ScopeDoc extends MethodDoc
{
    public function getTitle()
    {
        return Str::replaceFirst('scope', '', $this->reflection->name);
    }
}
