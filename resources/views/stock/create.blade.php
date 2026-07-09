@extends('layouts.app')

@section('title', __('app.stock_adjustment'))

@section('content')

<div class="page">

    <div class="page-header">

        <div>
            <h1 class="page-title">
                {{ __('app.stock_adjustment') }}
            </h1>

            <p class="page-subtitle">
                {{ __('app.stock_adjustment_note') }}
            </p>
        </div>

        <a href="{{ route('stock.index') }}" class="btn btn-secondary">
            &larr; {{ __('app.back_to_list') }}
        </a>

    </div>

    @if (session('error'))
        <div style="background:#fde2e2;color:#dc3545;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('stock.store') }}">
            @csrf

            <div class="form-row">

                <div class="form-group">
                    <label class="form-label">{{ __('app.product') }} <span class="required">*</span></label>
                    <select name="product_id" class="form-select">
                        <option value="">-- {{ __('app.select_product') }} --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                {{ $product->name }} ({{ $product->sku }}) &mdash; {{ __('app.current_stock') }}ঃ {{ $product->quantity }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.decrease_by') }} <span class="required">*</span></label>
                    <input type="number" name="quantity" class="form-control" min="1" value="{{ old('quantity') }}">
                    @error('quantity')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="form-row">

                <div class="form-group" style="grid-column:1 / -1;">
                    <label class="form-label">{{ __('app.reason') }} <span class="required">*</span></label>
                    <input type="text" name="note" class="form-control" placeholder="{{ __('app.reason_eg') }}" value="{{ old('note') }}">
                    @error('note')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <button type="submit" class="btn btn-primary">
                {{ __('app.save_adjustment') }}
            </button>

        </form>
    </div>

</div>

@endsection
