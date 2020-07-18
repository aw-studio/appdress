@extends('docs::app')

@section('content')
{!! app('docs.factory')->make(App\Models\Test::class) !!}
{!! app('docs.factory')->make(App\User::class) !!}
@endsection