@extends('docs::app')

@section('content')
{!! (new \Docs\Factory)->make(App\Models\Test::class) !!}
@endsection