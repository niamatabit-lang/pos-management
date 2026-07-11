<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('ui.app_name'))</title>

    {{-- Single Point Control: config/ui.php -> CSS variables --}}
    @include('layouts.partials.theme-vars')

    {{-- Stylesheet list is centrally defined in config/ui.php --}}
    @foreach (config('ui.stylesheets', []) as $stylesheet)
        <link rel="stylesheet" href="{{ asset($stylesheet) }}">
    @endforeach

    @stack('styles')
</head>

<body>

<div class="app">

    @include('layouts.sidebar')

    <div class="main">

        @include('layouts.header')

        <main class="content">

            @yield('content')

        </main>

        @include('layouts.footer')

    </div>

</div>

{{-- Script list is centrally defined in config/ui.php --}}
@foreach (config('ui.scripts', []) as $script)
    <script src="{{ asset($script) }}"></script>
@endforeach

@stack('scripts')

</body>
</html>
