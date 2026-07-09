@extends('layouts.app')

@section('title', __('app.edit_product'))

@section('content')

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('app.edit_product') }}</h1>
            <p class="page-subtitle">{{ __('app.edit_product_subtitle', ['name' => $product->name]) }}</p>
        </div>

        <a href="{{ route('products.index') }}" class="btn btn-secondary">&larr; {{ __('app.back_to_list') }}</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('products.update', $product) }}">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('app.name') }} <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}">
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">SKU <span class="required">*</span></label>
                    <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
                    @error('sku') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('app.category') }}</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- {{ __('app.select_category') }} --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.unit') }} <span class="required">*</span></label>
                    <input type="text" name="unit" class="form-control" value="{{ old('unit', $product->unit) }}">
                    @error('unit') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label">{{ __('app.buy_price') }} <span class="required">*</span></label>
                    <input type="number" step="0.01" name="buy_price" class="form-control" value="{{ old('buy_price', $product->buy_price) }}">
                    @error('buy_price') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.sale_price') }} <span class="required">*</span></label>
                    <input type="number" step="0.01" name="sell_price" class="form-control" value="{{ old('sell_price', $product->sell_price) }}">
                    @error('sell_price') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('app.reorder_level') }} <span class="required">*</span></label>
                    <input type="number" name="reorder_level" class="form-control" value="{{ old('reorder_level', $product->reorder_level) }}">
                    @error('reorder_level') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ __('app.commission_per_unit') }}</label>
                    <input type="number" step="0.01" name="commission" class="form-control" value="{{ old('commission', $product->commission) }}">
                    <small style="color:#888;">{{ __('app.commission_edit_note') }}</small>
                    @error('commission') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <p class="page-subtitle" style="margin-bottom:20px;">
                {{ __('app.current_stock') }}ঃ <strong>{{ $product->quantity }} {{ $product->unit }}</strong>
            </p>

            <button type="submit" class="btn btn-primary">{{ __('app.update_product') }}</button>
        </form>
    </div>

    <div class="card" style="margin-top:20px;">
        <h2 style="font-size:18px;margin-bottom:15px;">{{ __('app.restock') }}</h2>
        <p class="page-subtitle" style="margin-bottom:15px;">{{ __('app.restock_note') }}</p>

        <form method="POST" action="{{ route('products.restock', $product) }}">
            @csrf
            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label">{{ __('app.increase_by') }} <span class="required">*</span></label>
                    <input type="number" name="quantity" class="form-control" min="1" required>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('app.new_buy_price') }}</label>
                    <input type="number" step="0.01" name="new_buy_price" class="form-control" placeholder="{{ __('app.if_price_changed') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('app.note') }}</label>
                    <input type="text" name="note" class="form-control" placeholder="{{ __('app.eg_new_purchase') }}">
                </div>
            </div>
            <button type="submit" class="btn btn-secondary">{{ __('app.increase_stock') }}</button>
        </form>
    </div>

</div>

@endsection
