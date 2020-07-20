<?php

namespace Docs\Docs\Controller\Concerns;

use Docs\Docs\Controller\ControllerMethodDoc;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;

trait DescribesMethods
{
    /**
     * Describe route.
     *
     * @param  Collection $methods
     * @return array
     */
    public function describeMethods(Collection $methods)
    {
        if ($this->isInvokeOnly($methods)) {
            // Describe __invoke.
            return $this->subDoc(
                ControllerMethodDoc::class,
                $methods->first()
            )->getDescription();
        }

        return [
            $this->subTitle('Methods'),
        ];
    }

    protected function isInvokeOnly(Collection $methods)
    {
        return $methods->count() == 1 && $methods->first()->name == '__invoke';
    }
}
