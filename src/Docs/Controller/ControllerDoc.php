<?php

namespace Docs\Docs\Controller;

use Docs\Docs\ClassDoc;

class ControllerDoc extends ClassDoc
{
    use Concerns\ManagesRoutes,
        Concerns\DescribesMethods;

    public function describe()
    {
        return [
            $this->getSummary(),
            $this->describeMethods(
                $this->getOwnPublicMethods()
            ),
        ];
    }
}
