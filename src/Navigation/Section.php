<?php

namespace Docs\Navigation;

use Docs\Contracts\Doc;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\ClassLoader\ClassMapGenerator;

class Section
{
    protected $title;

    protected $children = [];

    protected $item;

    public function __construct($title, $item = null)
    {
        $this->title = $title;
        $this->item = $item;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function describeRoutes()
    {
        return $this->describe(__DIR__.'/../Docs/Routes');
    }

    public function describe($describe, $nested = false)
    {
        return $this->describePath($describe, $nested);
    }

    public function describePath($descriptionPath, $nested = false)
    {
        $map = ClassMapGenerator::createMap($descriptionPath);

        foreach ($map as $class => $path) {
            if (realpath(dirname($path)) != realpath($descriptionPath)) {
                continue;
            }

            if (! class_exists($class)) {
                continue;
            }

            if (class_is_abstract($class)) {
                continue;
            }

            $this->addChild(explode('.', basename($path))[0], $class);
        }

        if (! $nested) {
            return;
        }

        foreach (File::directories($descriptionPath) as $dir) {
            $this->describePath($dir, true);
        }
    }

    public function addChild($title, $item)
    {
        if (instance_of($item, Doc::class)) {
            $title = Str::replaceLast('Doc', '', $title);
        }

        $this->children[] = new self($title, $item);

        return $this;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getClass()
    {
        return $this->item;
    }

    public function route()
    {
        if (! $this->item) {
            return;
        }

        if (class_exists($this->item)) {
            return route('appdress.class', ['class' => $this->item]);
        }
    }
}
