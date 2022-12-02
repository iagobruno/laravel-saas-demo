@php
    $attrs = $attributes->class(['inline-block rounded-md bg-sky-600 py-1 px-3 whitespace-nowrap text-white hover:bg-sky-600/90']);
@endphp

@if ($attributes->has('href'))
    <a {{ $attrs }}>{{ $slot }}</a>
@else
    <button {{ $attrs }}>{{ $slot }}</button>
@endif
