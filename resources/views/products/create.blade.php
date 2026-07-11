@extends('layouts.app')

@section('title', __('app.add_product'))

@section('content')

<div class="page">

    <x-page-header :title="__('app.add_product')" :subtitle="__('app.add_product_subtitle')">
        <x-slot:actions>
            <x-button tag="a" href="{{ route('products.index') }}" variant="secondary">&larr; {{ __('app.back_to_list') }}</x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <form method="POST" action="{{ route('products.store') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('app.name') }} <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">SKU <span class="required">*</span></label>
                    <input type="text" name="sku" class="form-control" value="{{ old('sku') }}">
                    @error('sku') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('app.category') }}</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- {{ __('app.select_category') }} --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.unit') }} <span class="required">*</span></label>
                    <input type="text" name="unit" class="form-control" value="{{ old('unit', 'pcs') }}" placeholder="pcs, kg, ltr">
                    @error('unit') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row-4">
                <div class="form-group">
                    <label class="form-label">{{ __('app.buy_price') }} <span class="required">*</span></label>
                    <input type="number" step="0.01" name="buy_price" class="form-control" value="{{ old('buy_price') }}">
                    @error('buy_price') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.sale_price') }} <span class="required">*</span></label>
                    <input type="number" step="0.01" name="sell_price" class="form-control" value="{{ old('sell_price') }}">
                    @error('sell_price') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.opening_quantity') }} <span class="required">*</span></label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 0) }}">
                    @error('quantity') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.reorder_level') }} <span class="required">*</span></label>
                    <input type="number" name="reorder_level" class="form-control" value="{{ old('reorder_level', 5) }}">
                    @error('reorder_level') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('app.commission_per_unit') }}</label>
                    <input type="number" step="0.01" name="commission" class="form-control" value="{{ old('commission', 0) }}">
                    <small class="text-muted-note">{{ __('app.commission_note') }}</small>
                    @error('commission') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <x-button variant="primary">{{ __('app.save_product') }}</x-button>
        </form>
    </x-card>

</div>

@endsection
