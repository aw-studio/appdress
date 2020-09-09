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
            $this->getIntroduction(),

            $this->subTitle('Dependencies', 2),
            $this->describeDependencies(
                $this->reflectClassMethod($this->reflector, '__construct')
            ),

            $this->describeTests(),

            $this->describeMethods(
                $this->withoutMagic($this->getOwnPublicMethods())
            ),
        ];
    }
}
