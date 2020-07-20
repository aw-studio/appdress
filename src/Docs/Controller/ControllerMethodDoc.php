<?php

namespace Docs\Docs\Controller;

use Docs\Docs\MethodDoc;
use Docs\Support\Markdown;

class ControllerMethodDoc extends MethodDoc
{
    use Concerns\DescribesRequest,
        Concerns\ManagesRoutes;

    /**
     * Describe controller method.
     *
     * @return array
     */
    public function describe()
    {
        return [
            $this->describeRoute(),
            $this->describeRequest(),
        ];
    }

    /**
     * Describe route.
     *
     * @return array
     */
    protected function describeRoute()
    {
        if (! $route = $this->getRoute($this->reflector)) {
            return;
        }

        $items = [
            'Uri: '.Markdown::code('/'.$route->uri),
        ];

        if ($route->getName()) {
            $items[] = 'Uri: '.Markdown::code($route->getName());
        }

        return [
            'Route:',
            Markdown::list($items),
        ];
    }
}
