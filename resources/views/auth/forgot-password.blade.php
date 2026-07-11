@extends('layouts.auth')

@section('title', __('app.forgot_password'))
@section('subtitle', __('app.reset_password'))
@section('lang-switch')
@endsection

@section('content')

    @if (session('success'))
        <x-alert variant="success" size="sm">
            {{ session('success') }}
        </x-alert>
    @endif

    @if (session('error'))
        <x-alert variant="danger" size="sm">
            {{ session('error') }}
        </x-alert>
    @endif

    <p class="text-muted-note mb-18">
        {{ __('app.forgot_password_note') }}
    </p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">{{ __('app.email') }}</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" autofocus required>
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <x-button variant="primary" block>{{ __('app.send_reset_link') }}</x-button>
    </form>

    <div class="text-center mt-18">
        <a href="{{ route('login') }}" class="text-sm text-primary">&larr; {{ __('app.back_to_login') }}</a>
    </div>

@endsection
