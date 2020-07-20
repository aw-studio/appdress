@extends('docs::app')

@section('content')
{!! app('docs.factory')->make(App\Models\Booking::class)->toHtml() !!}
{{-- {!! app('docs.factory')->make(App\Http\Controllers\BookingController::class)->toHtml() !!} --}}
{{-- {!! app('docs.factory')->make(App\Models\Test::class)->toHtml() !!} --}}
{{-- {!! app('docs.factory')->make(App\User::class) !!} --}}
@endsection