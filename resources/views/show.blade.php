@extends('docs::app')

@section('content')
{!! (new \Docs\Factory)->make(App\Models\Test::class, Docs\Parser\ModelParser::class) !!}
@endsection