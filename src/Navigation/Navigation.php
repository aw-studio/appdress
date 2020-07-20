<?php

namespace Docs\Navigation;

class Navigation
{
    protected $sections = [];

    public function section($title)
    {
        return $this->sections[] = new Section($title);
    }

    public function getSections()
    {
        return $this->sections;
    }
}
