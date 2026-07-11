@extends('layouts.app')

@section('title', __('app.dashboard'))

@section('content')

<div class="page">

    <x-page-header :title="__('app.dashboard')">
        <x-slot:subtitle>
            {{ $currentShop->name }} —
            @if ($isToday)
                {{ __('app.today_summary') }}
            @else
                {{ $selectedDate->translatedFormat('d F, Y') }} {{ __('app.date_summary') }}
            @endif
        </x-slot:subtitle>

        <x-slot:actions>
            <form method="GET" action="{{ route('dashboard') }}" class="form-group-inline form-group-flush">
                <label for="dashboardDate" class="form-label mb-0 text-sm text-muted nowrap">
                    {{ __('app.select_date') }}
                </label>
                <input
                    type="date"
                    id="dashboardDate"
                    name="date"
                    class="form-input"
                    value="{{ $selectedDate->format('Y-m-d') }}"
                    max="{{ now()->format('Y-m-d') }}"
                    onchange="this.form.submit()">
                @unless ($isToday)
                    <x-button tag="a" href="{{ route('dashboard') }}" variant="secondary" size="sm">{{ __('app.back_to_today') }}</x-button>
                @endunless
            </form>
        </x-slot:actions>
    </x-page-header>

    @if ($lowStockProducts->isNotEmpty())
        <x-alert variant="warning">
            ⚠️ <strong>{{ __('app.low_stock_alert', ['count' => $lowStockProducts->count()]) }}</strong>
            {{ $lowStockProducts->pluck('name')->take(6)->implode(', ') }}
            @if ($lowStockProducts->count() > 6)
                {{ __('app.and_more', ['count' => $lowStockProducts->count() - 6]) }}
            @endif
        </x-alert>
    @endif

    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-title">
                @if ($isToday) {{ __('app.today_sales') }} @else {{ __('app.day_sales') }} @endif
            </div>
            <div class="kpi-value">৳ {{ number_format($daySalesTotal, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">
                @if ($isToday) {{ __('app.today_profit') }} @else {{ __('app.day_profit') }} @endif
            </div>
            <div class="kpi-value">৳ {{ number_format($dayProfit, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">
                @if ($isToday) {{ __('app.today_cash') }} @else {{ __('app.day_cash') }} @endif
            </div>
            <div class="kpi-value">৳ {{ number_format($dayCash, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">
                @if ($isToday) {{ __('app.today_expense') }} @else {{ __('app.day_expense') }} @endif
            </div>
            <div class="kpi-value">৳ {{ number_format($dayExpense, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.total_stock_value') }}</div>
            <div class="kpi-value">৳ {{ number_format($totalStockValue, 2) }}</div>
        </div>

    </div>

    <x-table-wrapper>

        <x-page-header :title="__('app.whats_in_stock')" flat class="mb-0">
            <x-slot:actions>
                @if (auth()->user()->hasPermission('products'))
                    <x-button tag="a" href="{{ route('products.index') }}" variant="secondary" size="sm">{{ __('app.view_all_products') }}</x-button>
                @endif
            </x-slot:actions>
        </x-page-header>

        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.product') }}</th>
                    <th class="text-right">{{ __('app.in_stock') }}</th>
                    <th class="text-right">{{ __('app.sale_price') }}</th>
                    <th class="text-right">{{ __('app.stock_value') }}</th>
                    <th>{{ __('app.status') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stockProducts as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td class="text-right">{{ number_format($product->quantity) }} {{ $product->unit }}</td>
                        <td class="text-right">৳ {{ number_format($product->sell_price, 2) }}</td>
                        <td class="text-right">৳ {{ number_format($product->quantity * $product->buy_price, 2) }}</td>
                        <td>
                            @if ($product->quantity == 0)
                                <x-badge variant="danger">{{ __('app.out_of_stock') }}</x-badge>
                            @elseif ($product->isLowStock())
                                <x-badge variant="warning">{{ __('app.low_stock') }}</x-badge>
                            @else
                                <x-badge variant="success">{{ __('app.ok') }}</x-badge>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="table-empty">
                            {{ __('app.no_products_yet') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </x-table-wrapper>

</div>

@endsection
