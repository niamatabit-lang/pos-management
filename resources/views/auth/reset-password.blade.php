@extends('layouts.auth')

@section('title', __('app.set_new_password'))
@section('subtitle', __('app.set_new_password'))

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

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label class="form-label">{{ __('app.email') }}</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $email) }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">{{ __('app.new_password') }}</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">{{ __('app.confirm_new_password') }}</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <x-button variant="primary" block>{{ __('app.reset_password_button') }}</x-button>
    </form>

@endsection
