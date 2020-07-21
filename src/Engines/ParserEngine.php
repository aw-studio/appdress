<?php

namespace Docs\Engines;

use Docs\Contracts\Doc;
use Docs\Contracts\Engine;
use Docs\Contracts\Parser;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class ParserEngine implements Engine
{
    protected $files;

    protected $parser;

    protected $cachePath;

    /**
     * Create new ParserEngine instance.
     *
     * @param  Filesystem $files
     * @param  Parser     $parser
     * @return void
     */
    public function __construct(Filesystem $files, Parser $parser)
    {
        $this->files = $files;
        $this->parser = $parser;
        $this->cachePath = storage_path('framework/docs');
    }

    public function getMarkdown(Doc $doc)
    {
        if ($this->isExpired($doc->getPath())) {
            $this->parse($doc);
        }

        return $this->files->get($this->getCompiledPath($doc->getPath()));
    }

    public function getHtml(Doc $doc)
    {
        return $this->parser->toHtml(
            $this->getMarkdown($doc)
        );
    }

    /**
     * Parse doc to markdown.
     *
     * @param  Doc  $doc
     * @return void
     */
    public function parse(Doc $doc)
    {
        $this->files->put(
            $this->getCompiledPath($doc->getPath()),
            $this->parser->toMarkdown($doc)
        );
    }

    /**
     * Determine if the file path at the given path is expired.
     *
     * @param  string $path
     * @return bool
     */
    public function isExpired($path)
    {
        if (config('app.debug')) {
            return true;
        }

        $compiled = $this->getCompiledPath($path);

        // If the compiled file doesn't exist we will indicate that the view is expired
        // so that it can be re-compiled. Else, we will verify the last modification
        // of the views is less than the modification times of the compiled views.
        if (! $this->files->exists($compiled)) {
            return true;
        }

        return $this->files->lastModified($path) >=
               $this->files->lastModified($compiled);
    }

    /**
     * Get the path to the compiled version of a docs markdown file.
     *
     * @param  string $path
     * @return string
     */
    public function getCompiledPath($path)
    {
        return $this->cachePath.'/'.sha1($path).'.md';
    }
}
