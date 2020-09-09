<?php

namespace Docs;

use Docdress\Parser;
use Docdress\Parser\AlertParser;
use Docdress\Parser\CodeParser;
use Docdress\Parser\LinkNameParser;
use Docdress\Parser\LinkParser;
use Docdress\Parser\SrcParser;
use Docdress\Parser\TocParser;
use Docs\Contracts\Doc;
use Docs\Contracts\Markdownable;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocsController
{
    /**
     * Filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Cache isntance.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * Parser instance.
     *
     * @var Parser
     */
    protected $parser;

    /**
     * Create new Documentor isntance.
     *
     * @param  Filesystem $files
     * @param  Cache      $cache
     * @param  Parser     $parser
     * @return void
     */
    public function __construct(Filesystem $files, Cache $cache, Parser $parser)
    {
        $this->files = $files;
        $this->cache = $cache;
        $this->parser = $parser;
    }

    /**
     * Show appdress docs.
     *
     * @param  Request     $request
     * @param  string|null $class
     * @return View
     */
    public function show(Request $request, $class = null)
    {
        $doc = '';

        if ($class) {
            $doc = $this->toHtml(app('appdress.factory')->make($class));
        }

        $index = $this->parseIndex(app('appdress.nav')->getSections());

        return view('docdress::docs', [
            'index'          => $index,
            'title'          => $class,
            'content'        => $doc,
            'versions'       => ['master' => 'Master'],
            'currentVersion' => 'master',
            'theme'          => config('docdress.themes.default'),
            'config'         => (object) [
                'route_prefix' => 'docs',
            ],
            'repo' => 'aw-studio/bassliner.org',
        ]);
    }

    /**
     * Parse doc to html.
     *
     * @param  Markdownable $docs
     * @return void
     */
    protected function toHtml(Markdownable $doc)
    {
        return $this->parser->parse($doc->toMarkdown(), [
            TocParser::class,
            AlertParser::class,
            CodeParser::class,
            SrcParser::class,
            LinkParser::class,
            LinkNameParser::class,
        ]);
    }

    /**
     * Parse index.
     *
     * @param  array $sections
     * @return void
     */
    protected function parseIndex($sections)
    {
        $markup = '';

        foreach ($sections as $section) {
            $markup .= '- ## '.$section->getTitle().'
';
            foreach ($section->getChildren() as $item) {
                $markup .= '    - ['.$item->getTitle().']('.$item->route().')
';
            }
        }

        return $this->parser->parse($markup, []);
    }
}
