@extends('layouts.app')

@section('title', __('app.change_password'))

@section('content')

<div class="page">

    <x-page-header :title="__('app.change_password')" :subtitle="__('app.change_password_note')" />

    @if (session('success'))
        <x-alert variant="success">
            {{ session('success') }}
        </x-alert>
    @endif

    @if (session('error'))
        <x-alert variant="danger">
            {{ session('error') }}
        </x-alert>
    @endif

    <x-card width="480">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">{{ __('app.current_password') }} <span class="required">*</span></label>
                <input type="password" name="current_password" class="form-control">
                @error('current_password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('app.new_password') }} <span class="required">*</span></label>
                <input type="password" name="new_password" class="form-control">
                <small class="text-muted-note">{{ __('app.min_6_chars') }}</small>
                @error('new_password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('app.confirm_new_password') }} <span class="required">*</span></label>
                <input type="password" name="new_password_confirmation" class="form-control">
            </div>

            <x-button variant="primary">{{ __('app.change_password_button') }}</x-button>
        </form>
    </x-card>

</div>

@endsection
