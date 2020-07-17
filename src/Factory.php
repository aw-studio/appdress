<?php

namespace Docs;

class Factory
{
    /**
     * Creates new Block object from namespace.
     *
     * @param  string $path
     * @return Block
     */
    public function make($namespace, string $parser): Block
    {
        $parser = new $parser($namespace);

        return $parser->parse();
    }
}
