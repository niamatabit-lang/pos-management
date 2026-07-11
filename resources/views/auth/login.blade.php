@extends('layouts.auth')

@section('title', __('app.login'))
@section('subtitle', __('app.login_to_account'))
@section('lang-switch')
@endsection

@section('content')

    @if (session('error'))
        <x-alert variant="danger" size="sm">
            {{ session('error') }}
        </x-alert>
    @endif

    @if ($errors->any())
        <x-alert variant="danger" size="sm">
            {{ $errors->first() }}
        </x-alert>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">{{ __('app.email') }}</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" autofocus required>
        </div>

        <div class="form-group">
            <label class="form-label">{{ __('app.password') }}</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-check mb-18 d-flex justify-between align-center">
            <div>
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" class="form-label mb-0">{{ __('app.remember_me') }}</label>
            </div>
            <a href="{{ route('password.request') }}" class="text-sm text-primary">{{ __('app.forgot_password') }}</a>
        </div>

        <x-button variant="primary" block>{{ __('app.login_button') }}</x-button>
    </form>

@endsection
