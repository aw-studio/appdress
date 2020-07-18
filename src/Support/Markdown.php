<?php

namespace Docs\Support;

use Docs\Markdown\Link;
use Docs\Markdown\MdList;
use Docs\Markdown\Table;
use Docs\Markdown\Title;

class Markdown
{
    public static function table($headers, $rows)
    {
        return new Table($headers, $rows);
    }

    public static function title($title, $depth = 1)
    {
        return new Title($title, $depth);
    }

    public static function list($items)
    {
        return new MdList($items);
    }

    public static function link($title, $href = null)
    {
        return new Link($title, $href);
    }
}
