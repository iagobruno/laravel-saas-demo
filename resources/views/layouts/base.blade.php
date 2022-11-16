<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="turbo-cache-control" content="no-preview">

    <title>
        @hasSection('page_title')
            @yield('page_title') -
        @endif{{ config('app.name') }}
    </title>

    @vite(['resources/js/app.js', 'resources/css/app.css'])

    @stack('extra_head')
</head>

<body class="@stack('body_class')">
    @yield('body')

    @stack('extra_body')
</body>

</html>
