<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Webte3 developers s.r.o.">
    @stack('meta')

    <title>Math-ify</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/navbar.css') }}?v{{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}?v{{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/sections.css') }}?v{{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/welcome.css') }}?v{{ time() }}" rel="stylesheet">
    @stack('style')
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3.0.1/es5/tex-mml-chtml.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.0/jspdf.umd.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
{{-- <header class="site-header"> --}}
@include('layouts.nav')
{{-- </header> --}}
<div id="app">
    @yield('content')
</div>
<script>
    window.csrfToken = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/teacher.js') }}" defer></script>
</html>
