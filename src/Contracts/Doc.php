<?php

namespace Docs\Contracts;

interface Doc
{
    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Get description.
     *
     * @return array
     */
    public function getDescription();

    /**
     * Get path of the documented file.
     *
     * @return string
     */
    public function getPath();
}
