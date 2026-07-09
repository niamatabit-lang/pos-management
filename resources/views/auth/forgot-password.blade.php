<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('app.forgot_password') }} - POS Management</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilities.css') }}">
</head>
<body style="background:var(--bg, #f4f6f9);min-height:100vh;display:flex;align-items:center;justify-content:center;">

    <div class="card" style="width:100%;max-width:400px;position:relative;">

        <form method="POST" action="{{ route('locale.switch', app()->getLocale() === 'bn' ? 'en' : 'bn') }}" style="position:absolute;top:16px;right:16px;">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm">
                @if (app()->getLocale() === 'bn') English @else বাংলা @endif
            </button>
        </form>

        <div style="text-align:center;margin-bottom:24px;">
            <div style="font-size:22px;font-weight:700;color:#198754;">POS Management</div>
            <p style="color:#888;margin-top:6px;">{{ __('app.reset_password') }}</p>
        </div>

        @if (session('success'))
            <div style="background:#d1f4df;color:#198754;padding:12px 16px;border-radius:10px;margin-bottom:18px;font-weight:600;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div style="background:#fde2e2;color:#dc3545;padding:12px 16px;border-radius:10px;margin-bottom:18px;font-weight:600;">
                {{ session('error') }}
            </div>
        @endif

        <p style="color:#888;font-size:13px;margin-bottom:18px;">
            {{ __('app.forgot_password_note') }}
        </p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">{{ __('app.email') }}</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" autofocus required>
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block">{{ __('app.send_reset_link') }}</button>
        </form>

        <div style="text-align:center;margin-top:18px;">
            <a href="{{ route('login') }}" style="font-size:13px;color:#198754;">&larr; {{ __('app.back_to_login') }}</a>
        </div>

    </div>

</body>
</html>
