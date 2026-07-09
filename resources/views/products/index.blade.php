@extends('layouts.app')

@section('title', __('app.nav_products'))

@section('content')

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">{{ __('app.nav_products') }}</h1>
            <p class="page-subtitle">{{ __('app.products_subtitle') }}</p>
        </div>

        <div style="display:flex;gap:10px;">
            <a href="{{ route('products.import.form') }}" class="btn btn-secondary">{{ __('app.bulk_import') }}</a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">+ {{ __('app.add_product') }}</a>
        </div>
    </div>

    @if (session('success'))
        <div style="background:#d1f4df;color:#198754;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="margin-bottom:20px;">
        <form method="GET" action="{{ route('products.index') }}" class="form-row-4">

            <div class="form-group search-box" style="margin-bottom:0;">
                <label class="form-label">{{ __('app.search') }}</label>
                <input type="text" name="search" class="form-control" placeholder="{{ __('app.search_name_sku') }}" value="{{ request('search') }}">
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">{{ __('app.category') }}</label>
                <select name="category_id" class="form-select">
                    <option value="">{{ __('app.all_categories') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group form-check" style="margin-bottom:0;align-items:flex-end;">
                <input type="checkbox" name="low_stock" id="low_stock" value="1" @checked(request('low_stock'))>
                <label for="low_stock" class="form-label" style="margin-bottom:0;">{{ __('app.only_low_stock') }}</label>
            </div>

            <div class="form-group" style="margin-bottom:0;display:flex;align-items:flex-end;gap:10px;">
                <button type="submit" class="btn btn-secondary">{{ __('app.filter') }}</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">{{ __('app.reset') }}</a>
            </div>

        </form>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.name') }}</th>
                    <th>SKU</th>
                    <th>{{ __('app.category') }}</th>
                    <th class="text-right">{{ __('app.buy_price') }}</th>
                    <th class="text-right">{{ __('app.sale_price') }}</th>
                    <th class="text-right">{{ __('app.commission') }}</th>
                    <th class="text-right">{{ __('app.stock') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th class="text-right">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td class="text-right">৳ {{ number_format($product->buy_price, 2) }}</td>
                        <td class="text-right">৳ {{ number_format($product->sell_price, 2) }}</td>
                        <td class="text-right">৳ {{ number_format($product->commission, 2) }}</td>
                        <td class="text-right">{{ number_format($product->quantity) }} {{ $product->unit }}</td>
                        <td>
                            @if ($product->quantity == 0)
                                <span class="badge badge-danger">{{ __('app.out_of_stock') }}</span>
                            @elseif ($product->isLowStock())
                                <span class="badge badge-warning">{{ __('app.low_stock') }}</span>
                            @else
                                <span class="badge badge-success">{{ __('app.in_stock') }}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="table-action" style="justify-content:flex-end;">
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary btn-sm">{{ __('app.edit') }}</a>
                                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('{{ __('app.are_you_sure') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">{{ __('app.delete') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="table-empty">{{ __('app.no_products_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="table-footer">
            <div>
                {{ __('app.showing_results', ['from' => $products->firstItem() ?? 0, 'to' => $products->lastItem() ?? 0, 'total' => $products->total()]) }}
            </div>

            {{ $products->links('vendor.pagination.custom') }}
        </div>
    </div>

</div>

@endsection
