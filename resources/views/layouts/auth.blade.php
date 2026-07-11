<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('ui.app_name') }}</title>

    @include('layouts.partials.theme-vars')

    @foreach (config('ui.auth_stylesheets', []) as $stylesheet)
        <link rel="stylesheet" href="{{ asset($stylesheet) }}">
    @endforeach

    @stack('styles')
</head>
<body class="auth-body">

    <x-card width="400" class="auth-card">

        @hasSection('lang-switch')
            <form method="POST" action="{{ route('locale.switch', app()->getLocale() === 'bn' ? 'en' : 'bn') }}" class="auth-lang-switch">
                @csrf
                <x-button variant="secondary" size="sm">
                    @if (app()->getLocale() === 'bn') English @else বাংলা @endif
                </x-button>
            </form>
        @endif

        <div class="auth-header">
            <div class="auth-logo">{{ config('ui.app_name') }}</div>
            <p class="auth-subtitle">@yield('subtitle')</p>
        </div>

        @yield('content')

    </x-card>

</body>
</html>
