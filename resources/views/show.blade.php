@extends('docs::app')

@section('content')
{!! app('docs.factory')->make(App\Models\Test::class) !!}
@endsection