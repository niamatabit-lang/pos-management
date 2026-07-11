@extends('layouts.app')

@section('title', __('app.nav_products'))

@section('content')

<div class="page">

    <x-page-header :title="__('app.nav_products')" :subtitle="__('app.products_subtitle')">
        <x-slot:actions>
            <x-button tag="a" href="{{ route('products.import.form') }}" variant="secondary">{{ __('app.bulk_import') }}</x-button>
            <x-button tag="a" href="{{ route('products.create') }}" variant="primary">+ {{ __('app.add_product') }}</x-button>
        </x-slot:actions>
    </x-page-header>

    @if (session('success'))
        <x-alert variant="success">
            {{ session('success') }}
        </x-alert>
    @endif

    <x-card class="mb-20">
        <form method="GET" action="{{ route('products.index') }}" class="form-row-4">

            <div class="form-group search-box form-group-flush">
                <label class="form-label">{{ __('app.search') }}</label>
                <input type="text" name="search" class="form-control" placeholder="{{ __('app.search_name_sku') }}" value="{{ request('search') }}">
            </div>

            <div class="form-group form-group-flush">
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

            <div class="form-group form-check form-group-flush align-end">
                <input type="checkbox" name="low_stock" id="low_stock" value="1" @checked(request('low_stock'))>
                <label for="low_stock" class="form-label mb-0">{{ __('app.only_low_stock') }}</label>
            </div>

            <div class="form-group form-group-flush form-group-inline">
                <x-button variant="secondary" type="submit">{{ __('app.filter') }}</x-button>
                <x-button tag="a" href="{{ route('products.index') }}" variant="secondary">{{ __('app.reset') }}</x-button>
            </div>

        </form>
    </x-card>

    <x-table-wrapper>
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
                                <x-badge variant="danger">{{ __('app.out_of_stock') }}</x-badge>
                            @elseif ($product->isLowStock())
                                <x-badge variant="warning">{{ __('app.low_stock') }}</x-badge>
                            @else
                                <x-badge variant="success">{{ __('app.in_stock') }}</x-badge>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="table-action justify-end">
                                <x-button tag="a" href="{{ route('products.edit', $product) }}" variant="secondary" size="sm">{{ __('app.edit') }}</x-button>
                                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('{{ __('app.are_you_sure') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" size="sm">{{ __('app.delete') }}</x-button>
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

        <x-slot:footer>
            <div>
                {{ __('app.showing_results', ['from' => $products->firstItem() ?? 0, 'to' => $products->lastItem() ?? 0, 'total' => $products->total()]) }}
            </div>

            {{ $products->links('vendor.pagination.custom') }}
        </x-slot:footer>
    </x-table-wrapper>

</div>

@endsection
