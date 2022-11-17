@extends('layouts.base')

@section('body')
    {{-- @include('layouts.partials.top-banner') --}}

    @if ($show_header ?? true)
        @include('layouts.partials.header')
    @endif

    {{-- @include('layouts.partials.flash-messages') --}}

    <main class="mx-auto max-w-7xl px-4 pb-5">
        @yield('content')
    </main>
@endsection
