@extends('layouts.app')

@section('title', __('app.nav_reports'))

@section('content')

<div class="page">

    <x-page-header :title="__('app.reports')" :subtitle="__('app.reports_subtitle')" />

    <x-card class="mb-20">
        <form method="GET" action="{{ route('reports.index') }}" class="form-row-4">

            <div class="form-group form-group-flush">
                <label class="form-label">{{ __('app.date_from') }}</label>
                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom->format('Y-m-d') }}">
            </div>

            <div class="form-group form-group-flush">
                <label class="form-label">{{ __('app.date_to') }}</label>
                <input type="date" name="date_to" class="form-control" value="{{ $dateTo->format('Y-m-d') }}">
            </div>

            <div class="form-group form-group-flush form-group-inline">
                <x-button variant="primary" type="submit">{{ __('app.view_report') }}</x-button>
                <x-button tag="a" href="{{ route('reports.index') }}" variant="secondary">{{ __('app.reset') }}</x-button>
                <x-button tag="a" href="{{ route('reports.export', ['date_from' => $dateFrom->toDateString(), 'date_to' => $dateTo->toDateString()]) }}" variant="secondary">📥 {{ __('app.export_csv') }}</x-button>
            </div>

        </form>

        <div class="mt-14 d-flex gap-10 flex-wrap">
            <x-button tag="a" href="{{ route('reports.index', ['date_from' => \Illuminate\Support\Carbon::today()->toDateString(), 'date_to' => \Illuminate\Support\Carbon::today()->toDateString()]) }}" variant="secondary" size="sm">{{ __('app.today') }}</x-button>
            <x-button tag="a" href="{{ route('reports.index', ['date_from' => \Illuminate\Support\Carbon::yesterday()->toDateString(), 'date_to' => \Illuminate\Support\Carbon::yesterday()->toDateString()]) }}" variant="secondary" size="sm">{{ __('app.yesterday') }}</x-button>
            <x-button tag="a" href="{{ route('reports.index', ['date_from' => \Illuminate\Support\Carbon::now()->startOfWeek()->toDateString(), 'date_to' => \Illuminate\Support\Carbon::now()->toDateString()]) }}" variant="secondary" size="sm">{{ __('app.this_week') }}</x-button>
            <x-button tag="a" href="{{ route('reports.index', ['date_from' => \Illuminate\Support\Carbon::now()->startOfMonth()->toDateString(), 'date_to' => \Illuminate\Support\Carbon::now()->toDateString()]) }}" variant="secondary" size="sm">{{ __('app.this_month') }}</x-button>
            <x-button tag="a" href="{{ route('reports.index', ['date_from' => \Illuminate\Support\Carbon::now()->subMonth()->startOfMonth()->toDateString(), 'date_to' => \Illuminate\Support\Carbon::now()->subMonth()->endOfMonth()->toDateString()]) }}" variant="secondary" size="sm">{{ __('app.last_month') }}</x-button>
        </div>
    </x-card>

    <p class="page-subtitle mb-16">
        {{ __('app.accounts_for_range', ['from' => $dateFrom->format('d M Y'), 'to' => $dateTo->format('d M Y')]) }}
    </p>

    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.total_sales') }}</div>
            <div class="kpi-value">৳ {{ number_format($totalSales, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.total_profit') }}</div>
            <div class="kpi-value">৳ {{ number_format($totalProfit, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.total_invoices') }}</div>
            <div class="kpi-value">{{ number_format($totalInvoices) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.total_items_sold') }}</div>
            <div class="kpi-value">{{ number_format($totalItemsSold) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.total_paid_cash') }}</div>
            <div class="kpi-value">৳ {{ number_format($totalPaid, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.total_due') }}</div>
            <div class="kpi-value">৳ {{ number_format($totalDue, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.service_fee_income') }}</div>
            <div class="kpi-value">৳ {{ number_format($serviceFeeCommission, 2) }}</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">{{ __('app.total_expense_kpi') }}</div>
            <div class="kpi-value">৳ {{ number_format($totalExpense, 2) }}</div>
        </div>

    </div>

    <x-table-wrapper padded class="mb-20">
        <h2 class="section-title">{{ __('app.sales_trend') }}</h2>
        <canvas id="salesTrendChart" height="80"></canvas>
    </x-table-wrapper>

    <x-table-wrapper class="mb-20">

        <x-page-header flat class="mb-0">
            <x-slot:heading>
                <h2 class="page-title text-lg">{{ __('app.whats_sold') }}</h2>
            </x-slot:heading>
        </x-page-header>

        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.product') }}</th>
                    <th class="text-right">{{ __('app.qty_sold') }}</th>
                    <th class="text-right">{{ __('app.total_revenue') }}</th>
                    <th class="text-right">{{ __('app.total_profit') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productWise as $row)
                    <tr>
                        <td>{{ $row['product_name'] }}</td>
                        <td class="text-right">{{ number_format($row['quantity']) }}</td>
                        <td class="text-right">৳ {{ number_format($row['revenue'], 2) }}</td>
                        <td class="text-right">৳ {{ number_format($row['profit'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="table-empty">{{ __('app.no_sales_in_range') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </x-table-wrapper>

    <x-table-wrapper>

        <x-page-header flat class="mb-0">
            <x-slot:heading>
                <h2 class="page-title text-lg">{{ __('app.invoice_list') }}</h2>
            </x-slot:heading>
        </x-page-header>

        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('app.invoice') }}</th>
                    <th>{{ __('app.date') }}</th>
                    <th>{{ __('app.customer') }}</th>
                    <th class="text-right">{{ __('app.total') }}</th>
                    <th class="text-right">{{ __('app.paid') }}</th>
                    <th class="text-right">{{ __('app.due') }}</th>
                    <th class="text-right">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales->sortByDesc('created_at') as $sale)
                    <tr>
                        <td>{{ $sale->invoice_no }}</td>
                        <td>{{ $sale->created_at->format('d M Y, h:i A') }}</td>
                        <td>{{ $sale->customer_name ?? __('app.walk_in_customer') }}</td>
                        <td class="text-right">৳ {{ number_format($sale->total, 2) }}</td>
                        <td class="text-right">৳ {{ number_format($sale->paid_amount, 2) }}</td>
                        <td class="text-right">৳ {{ number_format($sale->due_amount, 2) }}</td>
                        <td class="text-right">
                            <x-button tag="a" href="{{ route('sales.show', $sale) }}" variant="secondary" size="sm">{{ __('app.view') }}</x-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="table-empty">{{ __('app.no_invoices_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </x-table-wrapper>

</div>

@php
    $chartColor = config('ui.colors.primary');
    [$chartR, $chartG, $chartB] = sscanf($chartColor, "#%02x%02x%02x");
@endphp

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('salesTrendChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($trendLabels),
            datasets: [{
                label: '{{ __('app.daily_sales_taka') }}',
                data: @json($trendValues),
                borderColor: '{{ $chartColor }}',
                backgroundColor: 'rgba({{ $chartR }},{{ $chartG }},{{ $chartB }},0.1)',
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>

@endsection
