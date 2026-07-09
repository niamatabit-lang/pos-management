@extends('layouts.app')

@section('title', __('app.change_password'))

@section('content')

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('app.change_password') }}</h1>
            <p class="page-subtitle">{{ __('app.change_password_note') }}</p>
        </div>
    </div>

    @if (session('success'))
        <div style="background:#d1f4df;color:#198754;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="background:#fde2e2;color:#dc3545;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            {{ session('error') }}
        </div>
    @endif

    <div class="card" style="max-width:480px;">
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
                <small style="color:#888;">{{ __('app.min_6_chars') }}</small>
                @error('new_password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('app.confirm_new_password') }} <span class="required">*</span></label>
                <input type="password" name="new_password_confirmation" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">{{ __('app.change_password_button') }}</button>
        </form>
    </div>

</div>

@endsection
