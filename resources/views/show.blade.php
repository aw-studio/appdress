@extends('docs::app')

@section('content')
<<<<<<< HEAD
{{-- {!! app('docs.factory')->make(App\Models\Booking::class)->toHtml() !!} --}}
{!! app('docs.factory')->make(App\Http\Controllers\BookingController::class)->toHtml() !!}
=======

<div>
    {!! app('docs.factory')->make(App\Models\Booking::class)->toHtml() !!}
{{-- {!! app('docs.factory')->make(App\Http\Controllers\BookingController::class)->toHtml() !!} --}}
>>>>>>> 064981d107d795eb66096d66452f3759dc4c8dd2
{{-- {!! app('docs.factory')->make(App\Models\Test::class)->toHtml() !!} --}}
{{-- {!! app('docs.factory')->make(App\User::class) !!} --}}
</div>


@endsection