<?php

namespace Docs;

use Illuminate\Http\Request;

class DocsController
{
    public function class(Request $request, $class)
    {
        return view('docs::show', [
            'docs' => app('docs.factory')->make($class),
        ]);
    }

    public function index(Request $request)
    {
        return view('docs::index');
    }
}
