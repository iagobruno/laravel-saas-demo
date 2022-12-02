@extends('layouts.base')

@section('body')
    @include('layouts.partials.top-banner')

    @if ($show_header ?? true)
        @include('layouts.partials.header')
    @endif

    @include('layouts.partials.flash-messages')

    <main class="mx-auto max-w-5xl px-4 py-5">
        @yield('content')
    </main>
@endsection
