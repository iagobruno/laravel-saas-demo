@extends('layouts.base')

@section('body')
    {{-- @include('layouts.partials.top-banner') --}}

    {{-- @if ($show_header ?? true)
        @include('layouts.partials.header')
    @endif --}}

    {{-- @include('layouts.partials.flash-messages') --}}

    <main class="container pb-5">
        @yield('content')
    </main>
@endsection
