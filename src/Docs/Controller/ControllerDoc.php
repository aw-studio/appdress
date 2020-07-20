<?php

namespace Docs\Docs\Controller;

use Docs\Docs\ClassDoc;

class ControllerDoc extends ClassDoc
{
    public function title()
    {
        return class_basename($this->class);
    }

    public function describe()
    {
        return [
            $this->getSummary(),
        ];
    }
}
