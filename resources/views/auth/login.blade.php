<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('app.login') }} - POS Management</title>

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
            <p style="color:#888;margin-top:6px;">{{ __('app.login_to_account') }}</p>
        </div>

        @if (session('error'))
            <div style="background:#fde2e2;color:#dc3545;padding:12px 16px;border-radius:10px;margin-bottom:18px;font-weight:600;">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="background:#fde2e2;color:#dc3545;padding:12px 16px;border-radius:10px;margin-bottom:18px;font-weight:600;">
                {{ $errors->first() }}
            </div>
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

            <div class="form-check" style="margin-bottom:18px;display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember" class="form-label" style="margin:0;">{{ __('app.remember_me') }}</label>
                </div>
                <a href="{{ route('password.request') }}" style="font-size:13px;color:#198754;">{{ __('app.forgot_password') }}</a>
            </div>

            <button type="submit" class="btn btn-primary btn-block">{{ __('app.login_button') }}</button>
        </form>

    </div>

</body>
</html>
