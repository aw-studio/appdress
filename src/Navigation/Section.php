<?php

namespace Docs\Navigation;

use Illuminate\Support\Facades\File;
use Symfony\Component\ClassLoader\ClassMapGenerator;

class Section
{
    protected $title;

    protected $children = [];

    protected $class;

    public function __construct($title, $class = null)
    {
        $this->title = $title;
        $this->class = $class;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function describe($path)
    {
        $map = ClassMapGenerator::createMap($path);

        foreach (File::files($path) as $file) {
            if ($file->getExtension() != 'php') {
                continue;
            }
            foreach ($map as $class => $filePath) {
                if ($file->getPathname() == $filePath) {
                    $this->addChild(str_replace('.php', '', basename($file)), $class);
                }
            }
        }
    }

    public function addChild($title, $class)
    {
        $this->children[] = new self($title, $class);

        return $this;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function route()
    {
        if (! $this->class) {
            return;
        }

        return route('docs.class', ['class' => $this->class]);
    }
}
