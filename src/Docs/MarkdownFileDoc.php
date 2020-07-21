<?php

namespace Docs\Docs;

use Illuminate\Support\Facades\File;

class MarkdownFileDoc extends BaseDoc
{
    public function title()
    {
    }

    public function describe()
    {
        return File::get($this->path);
    }
}
