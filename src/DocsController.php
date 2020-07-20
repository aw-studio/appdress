<?php

namespace Docs;

use Illuminate\Http\Request;

class DocsController
{
    public function class(Request $request, $class)
    {
        return view('docs::show', [
            'class' => $class,
        ]);
    }
}
