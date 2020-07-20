<?php

namespace Docs\Support;

use Docs\Markdown\Code;
use Docs\Markdown\Link;
use Docs\Markdown\MdList;
use Docs\Markdown\Table;
use Docs\Markdown\Title;

class Markdown
{
    /**
     * Create markdown table.
     *
     * @param  array $headers
     * @param  array $rows
     * @return Table
     */
    public static function table($headers, $rows)
    {
        return new Table($headers, $rows);
    }

    /**
     * Create markdown title.
     *
     * @param  string $title
     * @param  int    $depth
     * @return Title
     */
    public static function title($title, $depth = 1)
    {
        return new Title($title, $depth);
    }

    /**
     * Create markdown list.
     *
     * @param  array  $items
     * @return MdList
     */
    public static function list($items)
    {
        return new MdList($items);
    }

    /**
     * Create markdown link.
     *
     * @param  string      $title
     * @param  string|null $href
     * @return Link
     */
    public static function link($title, $href = null)
    {
        return new Link($title, $href);
    }

    /**
     * Create markdown code.
     *
     * @param  string      $code
     * @param  string|null $lang
     * @return Code
     */
    public static function code($code, $lang = null)
    {
        return new Code($code, $lang);
    }
}
