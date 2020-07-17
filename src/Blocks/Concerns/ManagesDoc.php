<?php

namespace Docs\Blocks\Concerns;

trait ManagesDoc
{
    public function getDoc()
    {
        if ($this->doc) {
            return $this->doc;
        }

        if (! $comment = $this->reflection->getDocComment()) {
            return;
        }

        return $this->doc = $this->factory->create($comment);
    }
}
