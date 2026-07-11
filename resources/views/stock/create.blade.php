@extends('layouts.app')

@section('title', __('app.stock_adjustment'))

@section('content')

<div class="page">

    <x-page-header :title="__('app.stock_adjustment')" :subtitle="__('app.stock_adjustment_note')">
        <x-slot:actions>
            <x-button tag="a" href="{{ route('stock.index') }}" variant="secondary">&larr; {{ __('app.back_to_list') }}</x-button>
        </x-slot:actions>
    </x-page-header>

    @if (session('error'))
        <x-alert variant="danger">{{ session('error') }}</x-alert>
    @endif

    <x-card>
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

                <div class="form-group col-span-full">
                    <label class="form-label">{{ __('app.reason') }} <span class="required">*</span></label>
                    <input type="text" name="note" class="form-control" placeholder="{{ __('app.reason_eg') }}" value="{{ old('note') }}">
                    @error('note')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <x-button variant="primary">{{ __('app.save_adjustment') }}</x-button>

        </form>
    </x-card>

</div>

@endsection
