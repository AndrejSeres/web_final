<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Webte3 developers s.r.o.">
    @stack('meta')

    <title>@stack('title')</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/navbar.css') }}?v{{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}?v{{ time() }}" rel="stylesheet">    <link href="{{ asset('css/sections.css') }}?v{{ time() }}" rel="stylesheet">
    @stack('style')
    {{-- <link rel="icon" href="{{ asset('img/favicon.ico') }}"> --}}


    <script src="{{ asset('js/aos.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>

</body>
</html>
