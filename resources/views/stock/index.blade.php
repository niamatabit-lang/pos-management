@extends('layouts.app')

@section('title', __('app.nav_stock'))

@section('content')

<div class="page">

    <x-page-header :title="__('app.nav_stock')" :subtitle="__('app.stock_page_subtitle')">
        <x-slot:actions>
            <x-button tag="a" href="{{ route('stock.create') }}" variant="primary">+ {{ __('app.stock_adjustment') }}</x-button>
        </x-slot:actions>
    </x-page-header>

    @if (session('success'))
        <x-alert variant="success">{{ session('success') }}</x-alert>
    @endif

    @if (session('error'))
        <x-alert variant="danger">{{ session('error') }}</x-alert>
    @endif

    <x-card class="mb-20">
        <form method="GET" action="{{ route('stock.index') }}" class="form-row-3">

            <div class="form-group form-group-flush">
                <label class="form-label">{{ __('app.product') }}</label>
                <select name="product_id" class="form-select">
                    <option value="">{{ __('app.all_products') }}</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group form-group-flush">
                <label class="form-label">{{ __('app.type') }}</label>
                <select name="type" class="form-select">
                    <option value="">{{ __('app.all_types') }}</option>
                    <option value="out" @selected(request('type') == 'out')>{{ __('app.stock_decreased') }}</option>
                </select>
            </div>

            <div class="form-group form-group-flush form-group-inline">
                <x-button variant="secondary" type="submit">{{ __('app.filter') }}</x-button>
                <x-button tag="a" href="{{ route('stock.index') }}" variant="secondary">{{ __('app.reset') }}</x-button>
            </div>

        </form>
    </x-card>

    <x-table-wrapper>
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.date') }}</th>
                    <th>{{ __('app.product') }}</th>
                    <th>{{ __('app.type') }}</th>
                    <th class="text-right">{{ __('app.quantity') }}</th>
                    <th>{{ __('app.note') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($movements as $movement)
                    <tr>
                        <td>{{ $movement->created_at->format('d M Y, h:i A') }}</td>
                        <td>{{ $movement->product->name ?? 'N/A' }}</td>
                        <td>
                            @if ($movement->type === 'in')
                                <x-badge variant="success">{{ __('app.stock_in') }}</x-badge>
                            @else
                                <x-badge variant="danger">{{ __('app.stock_out') }}</x-badge>
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($movement->quantity) }}</td>
                        <td>{{ $movement->note ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="table-empty">
                            {{ __('app.no_stock_movements') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <x-slot:footer>
            <div>
                {{ __('app.showing_results', ['from' => $movements->firstItem() ?? 0, 'to' => $movements->lastItem() ?? 0, 'total' => $movements->total()]) }}
            </div>

            {{ $movements->links('vendor.pagination.custom') }}
        </x-slot:footer>
    </x-table-wrapper>

</div>

@endsection
