@extends('layouts.app')

@section('title', __('app.dashboard'))

@section('content')

<div class="page">

    <div class="page-header">
        <div>
            <h1 class="page-title">
                {{ __('app.dashboard') }}
            </h1>
            <p class="page-subtitle">
                {{ $currentShop->name }} —
                @if ($isToday)
                    {{ __('app.today_summary') }}
                @else
                    {{ $selectedDate->translatedFormat('d F, Y') }} {{ __('app.date_summary') }}
                @endif
            </p>
        </div>

        <form method="GET" action="{{ route('dashboard') }}" style="display:flex;align-items:center;gap:8px;">
            <label for="dashboardDate" style="font-size:13px;font-weight:600;color:#555;white-space:nowrap;">
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
                <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">{{ __('app.back_to_today') }}</a>
            @endunless
        </form>
    </div>

    @if ($lowStockProducts->isNotEmpty())
        <div style="background:#fff3cd;color:#856404;padding:14px 18px;border-radius:10px;margin-bottom:20px;">
            ⚠️ <strong>{{ __('app.low_stock_alert', ['count' => $lowStockProducts->count()]) }}</strong>
            {{ $lowStockProducts->pluck('name')->take(6)->implode(', ') }}
            @if ($lowStockProducts->count() > 6)
                {{ __('app.and_more', ['count' => $lowStockProducts->count() - 6]) }}
            @endif
        </div>
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

    <div class="table-wrapper">

        <div class="page-header" style="padding:18px 18px 0;">
            <h2 class="page-title" style="font-size:18px;">{{ __('app.whats_in_stock') }}</h2>
            @if (auth()->user()->hasPermission('products'))
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">{{ __('app.view_all_products') }}</a>
            @endif
        </div>

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
                                <span class="badge badge-danger">{{ __('app.out_of_stock') }}</span>
                            @elseif ($product->isLowStock())
                                <span class="badge badge-warning">{{ __('app.low_stock') }}</span>
                            @else
                                <span class="badge badge-success">{{ __('app.ok') }}</span>
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

    </div>

</div>

@endsection
