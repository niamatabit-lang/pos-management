@extends('layouts.app')

@section('title', __('app.bulk_import_title'))

@section('content')

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('app.bulk_import_title') }}</h1>
            <p class="page-subtitle">{{ __('app.bulk_import_subtitle') }}</p>
        </div>

        <a href="{{ route('products.index') }}" class="btn btn-secondary">&larr; {{ __('app.back_to_products') }}</a>
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

    @if (session('importErrors') && count(session('importErrors')))
        <div style="background:#fff3cd;color:#856404;padding:14px 18px;border-radius:10px;margin-bottom:20px;">
            <strong>{{ __('app.import_errors_found') }}</strong>
            <ul style="margin:8px 0 0 20px;">
                @foreach (session('importErrors') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="max-width:600px;">
        <p class="page-subtitle" style="margin-bottom:15px;">
            {{ __('app.csv_columns_note') }}
            <code>name, sku, category, unit, buy_price, sell_price, commission, quantity, reorder_level</code>
        </p>

        <a href="{{ route('products.import.sample') }}" class="btn btn-secondary" style="margin-bottom:20px;">{{ __('app.download_sample_csv') }}</a>

        <form method="POST" action="{{ route('products.import.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">{{ __('app.select_csv_file') }} <span class="required">*</span></label>
                <input type="file" name="file" class="form-control" accept=".csv,.txt" required>
                @error('file') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary">{{ __('app.import') }}</button>
        </form>
    </div>

</div>

@endsection
